<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\LinkCreation;
use OpenApi\Annotations as OA;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @Route("api/products")
 * 
 * @OA\Tag(name="products")
 * @Security(name="Bearer")
 */
class ProductController extends AbstractController
{

    private $linksCreation;

    public function __construct(LinkCreation $linksCreation)
    {
        $this->linksCreation = $linksCreation;
    }

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
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Number of the page",
     *     @OA\Schema(type="integer")
     * )
     * 
     */
    public function productsCollection(Request $request, ProductRepository $productRepository, CacheInterface $cache)
    {

        //configuration of limit and offset parameters
        $page = $request->get('page');
        $limit = 15;
        if ($page === null || $page < 1){
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        $json = $cache->get('products' . $page, function() use($productRepository,$offset,$limit){
            $products = array();
            foreach($productRepository->findAllByPage($limit,$offset) as $product){
                $product->setLinks($this->linksCreation->getLinks($product->getId(), 1, 0, 0));
                $products[] = $product;
            }
            return $this->json($products, 200, [], ['groups' => 'collection:product']);
        });

        return $json;
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
