<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("api/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route(name="api_users_collection_get", methods={"GET"})
     */
    public function usersCollection(UserRepository $userRepository, SerializerInterface $serializer)
    {
        //Récupération de la liste de tous les users
        // return $this->json($userRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'collection:user']);
        return new JsonResponse(
            $serializer->serialize($userRepository->findAll(), "json", ['groups' => 'collection:user']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @Route("/{id}", name="api_users_item_get", methods={"GET"})
     */
    public function usersItem(User $user, SerializerInterface $serializer)
    {
        if ($user->getClient() ==  $this->getUser()) {
            // return $this->json($user, Response::HTTP_OK, [], ['groups' => 'item:user']);
            return new JsonResponse(
                $serializer->serialize($user, "json", ['groups' => 'item:user']),
                Response::HTTP_OK,
                [],
                true
            );
        } else {
            // return $this->json("Cet utilisateur est lié à un autre client. Vous ne pouvez voir le détail que de vos utilisateurs.", Response::HTTP_FORBIDDEN);
            return new JsonResponse(
                [
                    "status" => 403,
                    "message" => "Cet utilisateur est lié à un autre client. Vous ne pouvez voir le détail que de vos utilisateurs."
                ],
                Response::HTTP_FORBIDDEN                
            );
        }
    }

    /**
     * @Route(name="api_users_collection_post", methods={"POST"})
     */
    public function post(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $user->setClient($this->getUser());

        $manager->persist($user);
        $manager->flush();

        return new JsonResponse([
            "status" => 201,
            "message" => "l'utilisateur à bien été crée"
        ], Response::HTTP_CREATED);
    }
}
