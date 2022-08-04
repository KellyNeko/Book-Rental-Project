<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
	 * @Route("/book/list", name="book_list")
	 */
    public function list(ManagerRegistry $doctrine): Response
    {
        // Récupération des Todos
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAll();

        // Appel de l'affichage, en passant les todos en paramètre
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

        return $this->render('book/show.html.twig', [
            'book' => $book_show
        ]);
    }

    /**
     * @Route("book/{id}/rent", name="book_rent")
     */
    // public function rent($id, ManagerRegistry $doctrine): Response
    // {
    //     $entityManager = $doctrine->getManager();

    //     $book_rent = new BookRenting();
    //     $book_rent->setUser('');
    //     $book_rent->setBook('');
    //     $product->setRentingStart('Ergonomic and stylish!');

    //     // tell Doctrine you want to (eventually) save the Product (no queries yet)
    //     $entityManager->persist($product);

    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();

    //     return new Response('Saved new product with id '.$product->getId());
    // }

}
