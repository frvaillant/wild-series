<?php


namespace App\DataFixtures;

use Faker;
use App\Entity\Actor;
use App\Service\DataMaker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const NUMBER_ACTORS = 30;
    const MAX_ACTORS_IN_PROGRAM = 5;
    const NUMBER_PROGRAMS = 12;


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < self::NUMBER_ACTORS; $i++) {
            $nbPrograms = rand(1, self::MAX_ACTORS_IN_PROGRAM);
            $actor = new Actor();
            $actor->setName($faker->firstName . ' ' . $faker->lastName);
            $actor->setLeadRole(1);
            for ($j = 0; $j < $nbPrograms; $j++) {
                $actor->addProgram($this->getReference('program_' . rand(0, self::NUMBER_PROGRAMS-1)));
            }
            $manager->persist($actor);
            $this->addReference(DataMaker::slugMaker('actor_' . $i), $actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

}