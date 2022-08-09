<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;
use App\Entity\BookRenting;
use App\Entity\Author;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookController extends AbstractController
{
    public $findQuery = "";
    /**
	 * @Route("/", name="app_book")
	 */
    // Return main page
    public function index()
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    
    //Call the command to return books that are rented for more than a month
    public function deleteOldRent(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:return-old-book-rent',
        ]);

        $output = new NullOutput();
        $application->run($input, $output);

        return new Response("");
    }

    /**
	 * @Route("/book/list", name="book_list")
	 */
    //List all not rented books
    public function list(ManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
    {
        // Get all free books from DB
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAllFree();

        $books = $this->paginatePages($paginator, $request, $books);

        $searchForm = $this->createSearchForm('book_handle_search');

        // Return layout for list of free books (not rented)
        return $this->render('book/list.html.twig', [
            'books' => $books,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("book/{id}/show", name="book_show")
     */
    //Show details of the book selected by the user
    public function show($id, ManagerRegistry $doctrine): Response
    {
        //Get the selected book
        $book_show = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        //Get the categories of the selected book
        $book_categories = $book_show->getBookCategories();

        //Return the layout of the book's details
        return $this->render('book/show.html.twig', [
            'book' => $book_show,
            'book_categories' => $book_categories
        ]);
    }

    /**
	 * @Route("/book/user/list", name="user_book_list")
	 */
    //List the books rented by the connected user
    public function userBookList(ManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
    {
        // Get all rented books from DB
        $repository = $doctrine->getRepository(Book::class);
        $user = $this->getUser();
        $books = $repository->findAllUserBooks($user);

        $books = $this->paginatePages($paginator, $request, $books);

        $searchForm = $this->createSearchForm('user_book_handle_search');

        // Return the layout of the user's books
        return $this->render('book/user_book_list.html.twig', [
            'books' => $books,
            'searchForm' => $searchForm->createView()
        ]);
    }

    /**
	 * @Route("/book/user/{id}/return", name="user_book_return")
	 */
    //Show the book to be returned by the user
    public function userBookReturn($id, ManagerRegistry $doctrine): Response
    {
        //Get the selected book
        $book_show = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        //Get the categories of the selected book
        $book_categories = $book_show->getBookCategories();

        //Return the layout of the book's details
        return $this->render('book/user_book_return.html.twig', [
            'book' => $book_show,
            'book_categories' => $book_categories
        ]);
    }

    /**
     * @Route("book/{id}/return", name="book_return")
     */
    public function bookReturn($id, ManagerRegistry $doctrine): Response
    {
        //Get the selected book
        $book = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        $book_renting = $book->getBookRentings();
        $entityManager = $doctrine->getManager();

        //Set the renting_end to the current date, the book is now returned
        foreach ($book_renting as $book_rent) {
            $book_rent->setRentingEnd(new \DateTime());
            $entityManager->persist($book_rent);
        }
        $entityManager->flush();

        //Redirect to the user's book list
        return $this->redirectToRoute('user_book_list');
    }

    /**
     * @Route("book/user/{id}/rent", name="book_user_rent")
     */
    public function userRent($id, ManagerRegistry $doctrine): Response
    {
        //Get the selected book
        $book_show = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        //Get the categories of the selected book
        $book_categories = $book_show->getBookCategories();

        //Return the layout of the book's details to rent
        return $this->render('book/user_rent.html.twig', [
            'book' => $book_show,
            'book_categories' => $book_categories
        ]);
    }

    /**
     * @Route("book/{id}/rent", name="book_rent")
     */
    public function rent($id, ManagerRegistry $doctrine): Response
    {
        //Create a new book renting
        $entityManager = $doctrine->getManager();
        $book_rent = new BookRenting();
        $book_rent->setUser($this->getUser());
        $book_rent->setBook($entityManager->getRepository(Book::class)->find($id));

        //Persist the new book rent in the db
        $entityManager->persist($book_rent);
        $entityManager->flush();

        return $this->redirectToRoute('book_list');
    }

    /**
     * @Route("book/search", name="book_handle_search")
     * @param Request $request
     */
    public function bookHandleSearch(Request $request, ManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {
        //Get the input of the user
        if(isset($request->request->all()['form']['query']))
        {
            $findQuery = $request->request->all()['form']['query'];
        }
        else {
            $findQuery ="";
        }

        //Get the books with the author searched by the user
        $authorSearchedBooks = $doctrine
            ->getRepository(Book::class)
            ->findByQuery($findQuery);

        $authorSearchedBooks = $this->paginatePages($paginator, $request, $authorSearchedBooks);
        
        $searchForm = $this->createSearchForm('book_handle_search');

        // Return layout for list of free books (not rented) filtered by authors
        return $this->render('book/list.html.twig', [
               'books' => $authorSearchedBooks,
               'searchForm' => $searchForm->createView()
        ]);
    }
    
    /**
     * @Route("book/user/search", name="user_book_handle_search")
     * @param Request $request
     */
    public function userBookHandleSearch(Request $request, ManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {
        //Get the input of the user
        if(isset($request->request->all()['form']['query']))
        {
            $findQuery = $request->request->all()['form']['query'];
        }
        else {
            $findQuery ="";
        }

        //Get the books with the author searched by the user
        $authorSearchedBooks = $doctrine
            ->getRepository(Book::class)
            ->findByQueryAndUser($findQuery, $this->getUser());
        
        $authorSearchedBooks = $this->paginatePages($paginator, $request, $authorSearchedBooks);
        
        $searchForm = $this->createSearchForm('user_book_handle_search');

        // Return layout for list of free books (not rented) filtered by authors, reference, or category
        return $this->render('book/user_book_list.html.twig', [
               'books' => $authorSearchedBooks,
               'searchForm' => $searchForm->createView()
        ]);
    }

    public function createSearchForm(string $route)
    {
        $searchForm = $this->createFormBuilder()
            ->setAction($this->generateUrl(route:$route))
            ->add('query', TextType::class)
            ->add('submit', SubmitType::class, ['label' => 'Rechercher',])
            ->getForm();

        return $searchForm ;
    }

    public function paginatePages(PaginatorInterface $paginator, Request $request, array $books)
    {
        $books = $paginator->paginate(
            $books, /* query NOT result */
            $request->query->getInt('page', 1),
            6
        );

       return $books;
    }
}
