<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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
        //Récupération de la liste de tous les users
        return $this->json($userRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'collection:user']);        
    }

    /**
     * @Route("/{id}", name="api_users_item_get", methods={"GET"})
     */
    public function usersItem(User $user)
    {
        if ($user->getClient() ==  $this->getUser()) {
            return $this->json($user, Response::HTTP_OK, [], ['groups' => 'item:user']);            
        } else {
            return $this->json(
                [
                    "status" => 403,
                    "Cet utilisateur est lié à un autre client. Vous ne pouvez voir le détail que de vos utilisateurs."
                ], 
                Response::HTTP_FORBIDDEN);            
        }
    }

    /**
     * @Route(name="api_users_item_post", methods={"POST"})
     */
    public function post(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        try{
            $user = $serializer->deserialize($request->getContent(), User::class, 'json');

            $user->setClient($this->getUser());

            $errors = $validator->validate($user);

            if(count($errors) > 0){
                return $this->json($errors, 400);
            }

            $manager->persist($user);
            $manager->flush();

            return $this->json($user, 201, [], ['groups' => 'item:user']);

        } catch(NotEncodableValueException $e){
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }
        
    }

    /**
     * @Route("/{id}", name="api_users_item_put", methods={"PUT"})
     */
    public function put(User $user, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $manager->persist($user);
        $manager->flush();

        return $this->json($user, 200, [], ['groups' => 'item:user']);

    }

    /**
     * @Route("/{id}", name="api_users_item_delete", methods={"DELETE"})
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {

        $manager->remove($user);
        $manager->flush();

        return $this->json(null, 204);
    }
}
