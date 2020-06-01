<?php


namespace App\DataFixtures;

use Faker;
use App\Entity\Program;
use App\Service\DataMaker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'Des étoiles et des hommes',
        'En route vers',
        'Le noir est une couleur brillante',
        'Revenir des enfers',
        'Marcheurs de gloire',
        'Cosmos trip',
        'Nuages roses et bonbons maudits',
        'Cesleste',
        'Galaxies et nébuleuses',
        'Le téléscope enchanté',
        'Partis pour le centaure',
        'Retour du trou noir',
    ];


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        //
        for ($i = 0; $i < count(self::PROGRAMS); $i++) {
            $program = new Program();
            $programTitle=DataMaker::removeSpecialCharacters(self::PROGRAMS[$i]);
            $program->setTitle($programTitle);
            $program->setSummary($faker->realText());
            $program->setPoster(DataMaker::getPicture());
            $program->setCategory($this->getReference('category_' . rand(0,7)));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}