<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("api/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route(name="api_users_collection_get", methods={"GET"})
     */
    public function usersCollection(UserRepository $userRepository)
    {
        return $this->json($userRepository->findByClient($this->getUser()->getId()), Response::HTTP_OK, [], ['groups' => 'collection:user']);
    }

    /**
     * @Route("/{id}", name="api_users_collection_get", methods={"GET"})
     */
    public function usersItem(User $user)
    {
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'item:user']);
    }
}
