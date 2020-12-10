<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        $brands = ['samsung', 'apple', 'huawei', 'oppo', 'xiaomi'];

        //creation of the products
        foreach ($brands as $brand){

            for ($i = 0; $i < 5; $i++){

                $product = new Product();

                $product->setName($brand . " " . $faker->randomLetter() . $faker->numberBetween(10, 100))
                        ->setBrand($brand)
                        ->setDescription($faker->text(200))
                        ->setScreenSize($faker->randomFloat(2,5,8) . " pouces")
                        ->setReleaseDate($faker->dateTimeBetween('-5 years', 'now', null));

                $manager->persist($product);

            }

        }

        //Creation of the clients
        for ($i = 0; $i < 5; $i++){

            $client = new Client();

            $client->setName($faker->company())
                   ->setAdress($faker->streetAddress())
                   ->setCodePostal($faker->postCode())
                   ->setTown($faker->city())
                   ->setCountry($faker->country())
                   ->setPhoneNumber($faker->phoneNumber())
                   ->setEmail($faker->email());

            $manager->persist($client);

            //Creation of the Users
            for ($j= 0; $j < 5; $j++){

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
