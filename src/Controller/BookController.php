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
    public function index()
    {
        // Call main page
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    
    //Function calling command for deleting books that are not rented anymore
    public function deleteOldRent(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:delete-old-book-rent',
        ]);

        $output = new NullOutput();
        $application->run($input, $output);

        return new Response("");
    }

    /**
	 * @Route("/book/list", name="book_list")
	 */
    public function list(ManagerRegistry $doctrine): Response
    {
        // Get all books from DB
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAll();

        // Call Layout for list of books
        return $this->render('book/list.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("book/{id}/show", name="book_show")
     */
    public function show($id, ManagerRegistry $doctrine): Response
    {
        $book_show = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        $book_categories = $book_show->getBookCategories();

        return $this->render('book/show.html.twig', [
            'book' => $book_show,
            'book_categories' => $book_categories
        ]);
    }

    /**
	 * @Route("/book/user/list", name="user_book_list")
	 */
    public function userBookList(ManagerRegistry $doctrine): Response
    {
        // Get all books from DB
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAll();

        // Call Layout for list of user's books
        return $this->render('book/user_book_list.html.twig', [
            'books' => $books
        ]);
    }

    /**
	 * @Route("/book/user/{id}/return", name="user_book_return")
	 */
    public function userBookReturn($id, ManagerRegistry $doctrine): Response
    {
        $book_show = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        $book_categories = $book_show->getBookCategories();

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
        $book = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        $book_rent_delete = $book->getBookRentings();
        $entityManager = $doctrine->getManager();

        foreach ($book_rent_delete as $book_rent_del) {
            $entityManager->remove($book_rent_del);
        }
        $entityManager->flush();

        return $this->redirectToRoute('user_book_list');
    }

    /**
     * @Route("book/user/{id}/rent", name="book_user_rent")
     */
    public function userRent($id, ManagerRegistry $doctrine): Response
    {
        $book_show = $doctrine
            ->getRepository(Book::class)
            ->find($id);

        $book_categories = $book_show->getBookCategories();

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
        $entityManager = $doctrine->getManager();
        $book_rent = new BookRenting();
        $book_rent->setUser($this->getUser());
        $book_rent->setBook($entityManager->getRepository(Book::class)->find($id));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($book_rent);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('book_list');
    }
}
