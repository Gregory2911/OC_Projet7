<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        $brands = ['samsung', 'apple', 'huawei', 'oppo', 'xiaomi'];

        //creation of the products
        foreach ($brands as $brand) {

            for ($i = 0; $i < 5; $i++) {

                $product = new Product();

                $product->setName($brand . " " . $faker->randomLetter() . $faker->numberBetween(10, 100))
                    ->setBrand($brand)
                    ->setDescription($faker->text(200))
                    ->setScreenSize($faker->randomFloat(2, 5, 8) . " pouces")
                    ->setReleaseDate($faker->dateTimeBetween('-5 years', 'now', null));

                $manager->persist($product);
            }
        }

        //Creation of the clients
        for ($i = 0; $i < 5; $i++) {

            $client = new Client();

            if ($i == 0) {
                $client->setName("Grégory AGNAN")
                    ->setAdress("01 impasse des jardins de coavou")
                    ->setCodePostal("22980")
                    ->setTown("VILDE GUINGALAN")
                    ->setCountry("FRANCE")
                    ->setPhoneNumber("0958694425")
                    ->setEmail("admin@bilemo.com")
                    ->setPassword($this->encoder->encodePassword($client, "password"));                    
            } else {
                $client->setName($faker->company())
                ->setAdress($faker->streetAddress())
                ->setCodePostal($faker->postCode())
                ->setTown($faker->city())
                ->setCountry($faker->country())
                ->setPhoneNumber($faker->phoneNumber())
                ->setEmail($faker->email())
                ->setPassword($this->encoder->encodePassword($client, "password"));
            }

            $manager->persist($client);

            //Creation of the Users
            for ($j = 0; $j < 5; $j++) {

                $user = new User();

                $user->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setAdress($faker->streetAddress())
                    ->setCodePostal($faker->postCode())
                    ->setTown($faker->city())
                    ->setCountry($faker->country())
                    ->setPhoneNumber($faker->phoneNumber())
                    ->setEmail($faker->email())
                    ->setClient($client);

                $manager->persist($user);
            }
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
