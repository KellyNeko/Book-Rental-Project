<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\BookCategory;

//Populate Author, Category, Book and BookCategory
class BookCategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 20; $i++){
            $category = $this->getReference('category_'. mt_rand(1, 10));
            $book = $this->getReference('book_'. mt_rand(0, 19));
            $book_category = new BookCategory();
            $book_category->setCategory($category);
            $book_category->setBook($book);
            $manager->persist($book_category);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookFixtures::class,
            CategoryFixtures::class
        ];
    }
}
