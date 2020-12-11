<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiPostController extends AbstractController
{
    /**
     * @Route("/api/products", name="api_products_collection_get", methods={"GET"})
     */
    public function productsCollection(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {

        $json = $serializer->serialize($productRepository->findAll(), 'json');
                
        $response = new JsonResponse($json, 200, [], true);

        return $response;

    }
}
