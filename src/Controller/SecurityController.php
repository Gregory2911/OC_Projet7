<?php

namespace App\Controller;

use App\Entity\Client;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/login_check")
 * @OA\Tag(name="connexion")
 **/

class SecurityController extends AbstractController
{

    /**
     * @Route(name="login_check", methods={"POST"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns a connexion token",
     * )
     * * @OA\Response(
     *     response=401,
     *     description="Invalid credentials",
     * )
     * @OA\RequestBody(
     *     request="connexion",
     *     description="login id",
     *     required=true,
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref=@Model(type=Client::class, groups={"connexion"}))
     *     )
     * )
     * 
     */
    public function index()
    {
        // return $this->render('security/index.html.twig', [
        //     'controller_name' => 'SecurityController',
        // ]);
    }
}
