<?php


namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const  CATEGORIES = [
            'thriller',
            'romance',
            'policier',
            'horreur',
            'action',
            'famille',
            'enfance',
            'aventure',
        ];

    public function load (ObjectManager $manager)
    {
        $key=0;
            foreach (self::CATEGORIES as $catName ) {
                $category = new Category();
                $category->setName($catName);
                $manager->persist($category);
                $this->addReference('category_' . $key, $category);
                $key++;
            }
        $manager->flush();
    }


}