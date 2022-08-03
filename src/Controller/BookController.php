<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    /**
	 * @Route("/", name="app_book")
	 */
    public function list()
    {
        // Récupération des Livres
        // $repository = $this->getDoctrine()->getRepository(Todo::class);
        // $todos = $repository->findAll();

        // Appel de l'affichage, en passant le nom en paramètres
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

}
