<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;

//Populate Author, Category, Book and BookCategory
class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            1 => [
                'label' => 'Roman'
            ],
            2 => [
                'label' => 'Essai'
            ],
            3 => [
                'label' => 'Recueil'
            ],
            4 => [
                'label' => 'Nouvelle '
            ],
            5 => [
                'label' => 'Conte'
            ],
            6 => [
                'label' => 'Biographie'
            ],
            7 => [
                'label' => 'Chronique'
            ],
            8 => [
                'label' => 'Mythe'
            ],
            9 => [
                'label' => 'Légende'
            ],
            10 => [
                'label' => 'Journal'
            ],
        ];

        foreach($categories as $key => $value){
            $category = new Category();
            $category->setLabel($value['label']);
            $manager->persist($category);

            // Save category in a reference
            $this->addReference('category_' . $key, $category);
        }

        $manager->flush();
    }
}
