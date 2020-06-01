<?php


namespace App\DataFixtures;

use Faker;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const NUMBER_PROGRAMS = 12;
    const MAX_SEASONS_IN_PROGRAM = 7;

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        //
        $key=0;
        for ($i = 0; $i < self::NUMBER_PROGRAMS; $i++) {
            $seasonNumber = rand(1, self::MAX_SEASONS_IN_PROGRAM);
            for ($j = 1; $j <= $seasonNumber; $j++) {
                $season = new Season();
                $season->setNumber($j);
                $season->setDescription($faker->realText(150));
                $season->setYear($faker->year);
                $season->setProgram($this->getReference('program_' . $i));
                $manager->persist($season);
                $this->addReference('season_' . $key, $season);
                $key++;
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

}