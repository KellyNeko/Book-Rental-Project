<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\AuthorRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
* @Route("/api/author")
* Class AuthorAPIController
*/
class AuthorAPIController extends AbstractController
{
   /**
     * @Route("/show/{id}", name="author_api_show", methods={"GET"})
     * @param AuthorRepository $authorRepository
     * @return JsonResponse
     */
    public function showAuthorAPI(AuthorRepository $authorRepository, int $id): JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $author = $authorRepository->find($id);
        $jsonContent = $serializer->serialize($author, 'json');

        return new JsonResponse(['author' => $jsonContent]);

    }
}
