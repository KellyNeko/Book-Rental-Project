<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

//Populate Author, Category, Book and BookCategory
class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // create 20 Books!
        for ($i = 0; $i < 20; $i++) {
            $author = $this->getReference('author_'. mt_rand(1, 10));
            $book = new Book();
            $book->setReference('reference'.$i);
            $book->setAuthor($author);
            $manager->persist($book);

            $this->addReference('book_' . $i, $book);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AuthorFixtures::class
        ];
    }
}
