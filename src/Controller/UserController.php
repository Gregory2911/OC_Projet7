<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\LinkCreation;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

/**
 * @Route("api/users")
 * @OA\Tag(name="users")
 * @Security(name="Bearer")
 */
class UserController extends AbstractController
{

    private $linksCreation;

    public function __construct(LinkCreation $linksCreation)
    {
        $this->linksCreation = $linksCreation;
    }

    /**
     * @Route(name="api_users_collection_get", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of users",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"collection:user"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Number of the page",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="idClient",
     *     in="query",
     *     description="Identifiant du client",
     *     @OA\Schema(type="integer")
     * )
     * 
     */
    public function usersCollection(Request $request, UserRepository $userRepository)
    {

        //configuration of limit and offset parameters
        $page = $request->get('page');
        
        $limit = 15;
        if ($page === null || $page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        
        //Retrieving the list of users with optional parameters limit, offset, idClient
        $users = array();
        foreach($userRepository->findAllByPage($limit, $offset, $request->get('idClient')) as $user){            
            $user->setLinks($this->linksCreation->getLinks($user->getId(),1,1,1,'users'));
            $users[] = $user;            
        }
        
        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'collection:user']);        
    }

    /**
     * @Route("/{id}", name="api_users_item_get", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"item:user"}))
     *     )
     * )
     * 
     * @OA\Response(
     *     response=403,
     *     description="Cet utilisateur est lié à un autre client. Vous ne pouvez voir le détail que de vos utilisateurs."
     * )
     * 
     
     */
    public function usersItem(User $user)
    {
        if ($user->getClient() ==  $this->getUser()) {

            $user->setLinks($this->linksCreation->getLinks($user->getId(), 0, 1, 1, 'users'));

            return $this->json($user, Response::HTTP_OK, [], ['groups' => 'item:user']);            
        } else {
            return $this->json(
                [
                    "status" => 403,
                    "message" => "Cet utilisateur est lié à un autre client. Vous ne pouvez voir le détail que de vos utilisateurs."
                ], 
                Response::HTTP_FORBIDDEN);            
        }
    }

    /**
     * @Route(name="api_users_item_post", methods={"POST"})
     * @OA\Response(
     *     response=201,
     *     description="Adding a user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"item:user"}))
     *     )
     * )
     * 
     * 
     * @OA\Response(
     *     response=400,
     *     description="Error adding the user",
     * )
     * 
     
     * 
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
     * @OA\Response(
     *     response=200,
     *     description="Update a user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class, groups={"item:user"}))
     *     )
     * )
     * 
     * 
     * @OA\Response(
     *     response=400,
     *     description="Error updating the user",
     * )
     * 
     
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
     * @OA\Response(
     *     response=204,
     *     description="delete a user",
     * )
     *      
     */
    public function delete(User $user, EntityManagerInterface $manager)
    {

        $manager->remove($user);
        $manager->flush();

        return $this->json(null, 204);
    }
}
