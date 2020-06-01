<?php


namespace App\DataFixtures;

use App\Repository\SeasonRepository;
use Faker;
use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const MAX_EPISODES_IN_SEASON = 10;

    public function load(ObjectManager $manager)
    {
        $numberOfSeasons = $manager->getRepository(Season::class)->countAll();
        $faker = Faker\Factory::create('fr_FR');
        //
        $key=0;
        for ($i = 0; $i < $numberOfSeasons; $i++) {
            $episodeNumber = rand(1, self::MAX_EPISODES_IN_SEASON);
            for ($j = 1; $j <= $episodeNumber; $j++) {
                $episode = new Episode();
                $episode->setNumber($j);
                $episode->setTitle($faker->sentence(rand(6,15)));
                $episode->setSynopsis($faker->realText());
                $episode->setSeason($this->getReference('season_' . $i));
                $manager->persist($episode);
                $this->addReference('episode_' . $key, $episode);
                $key++;
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }

}