<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\BookRenting;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class BookController extends AbstractController
{
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
    public function list(ManagerRegistry $doctrine): Response
    {
        // Get all free books from DB
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAllFree();

        // Return layout for list of free books (not rented)
        return $this->render('book/list.html.twig', [
            'books' => $books
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
    public function userBookList(ManagerRegistry $doctrine): Response
    {
        // Get all rented books from DB
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAllUserBooks();

        // Return the layout of the user's books
        return $this->render('book/user_book_list.html.twig', [
            'books' => $books
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
}
