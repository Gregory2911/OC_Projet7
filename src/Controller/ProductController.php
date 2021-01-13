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
 * @OA\Tag(name="products")
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
     */
    public function productsCollection(ProductRepository $productRepository)
    {
        $products = array();
        $i = 0;
        foreach($productRepository->findAll() as $value){
            $product = new Product;
            $product = $value;
            $links = array();
            $links = ['self' => '/api/products/' . $product->getId()];
            $product->setLinks($links);
            $products[$i] = $product;
            $i++;
        }

        //dd($products);

        //return $this->json($productRepository->findAll(), 200, [], ['groups' => 'collection:product']);
        return $this->json($products, 200, [], ['groups' => 'collection:product']);
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
     */
    public function productItem(Product $product)
    {
        return $this->json($product, 200, [], ['groups' => 'item:product']);
    }
}
