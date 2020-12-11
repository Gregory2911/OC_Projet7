<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("api/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Route(name="api_products_collection_get", methods={"GET"})
     */
    public function productsCollection(ProductRepository $productRepository)
    {

        return $this->json($productRepository->findAll(), 200, [], ['groups' => 'collection:product']);

    }

    /**
     * @Route("/{id}", name="api_products_item_get", methods={"GET"})
     */
    public function productItem(Product $product)
    {
        return $this->json($product, 200, [], ['groups' => 'item:product']);
    }
}
