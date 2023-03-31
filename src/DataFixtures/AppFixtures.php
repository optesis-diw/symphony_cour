<?php

namespace App\DataFixtures;


use Faker\Factory;

use App\Entity\Ingredient;
use Faker\Generator;


use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;



class AppFixtures extends Fixture
{

	/**
     * @var Generator: Faker est une bibliothèque PHP qui génère de fausses données :name, image , date....
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }




    public function load(ObjectManager $manager): void
    {

    	//charger la table ingredient de la base de donné avec 50 donné les names seront recupéré a partir du faker
        #$ingredients = [];
        for ($i = 0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word()) #word phrase avec 1 seul mot
                ->setPrice(mt_rand(0, 100));
                #->setUser($users[mt_rand(0, count($users) - 1)]);

            #$ingredients[] = $ingredient; 
            $manager->persist($ingredient); #ajout sur la bd
        }



    	
	    $manager->flush();
    }
}
