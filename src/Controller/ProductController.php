<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("api/products")
 * 
 * @Security(name="Bearer")
 */
class ProductController extends AbstractController
{
    /**
     * Returns the list of products
     * 
     * @Route(name="api_products_collection_get", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"collection:product"}))
     *     )
     * )
     * 
     * @OA\Tag(name="list of products")
     */
    public function productsCollection(ProductRepository $productRepository)
    {
        return $this->json($productRepository->findAll(), 200, [], ['groups' => 'collection:product']);
    }

    /**
     * Returns the details of a products
     * 
     * @Route("/{id}", name="api_products_item_get", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"item:product"}))
     *     )
     * )
     * 
     * @OA\Tag(name="details of a product")
     */
    public function productItem(Product $product)
    {
        return $this->json($product, 200, [], ['groups' => 'item:product']);
    }
}
