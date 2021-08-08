<?php

namespace App\DataFixtures;

use App\Entity\Pin;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($j=0; $j <3;$j++){
            // Utilisateur de Faker
            $faker = Factory::create('fr Fr');

            // Création d'un utilisateur
            $user = new User;
            $user->setFirstName($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setPassword('$2y$13$mCRHAspEn8iC1liLZefNYeAjC0r/E.AkXgIikemICcNiEYpoCPP4.');

            $fakeName =$user->getFirstName();
            $user->setEmail("$fakeName@test.fr");

            $manager->persist($user);

            //création d'un pin pour l'utilisateur
            for ($i=0; $i <3; $i++){
                $pin = new Pin;
                $pin->setTitle((string)$faker->words(2, true))
                    ->setDescription($faker->text(20))
                    ->setUser($user);
                $manager->persist($pin);
            }
        }

        $manager->flush();
    }
}
