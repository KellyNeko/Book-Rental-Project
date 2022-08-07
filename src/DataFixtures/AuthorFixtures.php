<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;

//Populate Author, Category, Book and BookCategory
class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $authors = [
            1 => [
                'last_name' => 'Poquelin',
                'first_name' => 'Jean-Baptiste'
            ],
            2 => [
                'last_name' => 'Proust',
                'first_name' => 'Marcel'
            ],
            3 => [
                'last_name' => 'Flaubert',
                'first_name' => 'Gustave'
            ],
            4 => [
                'last_name' => 'Sartre',
                'first_name' => 'Jean-Paul'
            ],
            5 => [
                'last_name' => 'De Beauvoir',
                'first_name' => 'Simone'
            ],
            6 => [
                'last_name' => 'Camus',
                'first_name' => 'Albert'
            ],
            7 => [
                'last_name' => 'Baudelaire',
                'first_name' => 'Charles'
            ],
            8 => [
                'last_name' => 'Zola',
                'first_name' => 'Émile'
            ],
            9 => [
                'last_name' => 'Rimbaud',
                'first_name' => 'Arthur'
            ],
            10 => [
                'last_name' => 'Verlaine',
                'first_name' => 'Paul'
            ],
        ];

        foreach($authors as $key => $value){
            $author = new Author();
            $author->setLastName($value['last_name']);
            $author->setFirstname($value['first_name']);
            $manager->persist($author);

            // Save author in a reference
            $this->addReference('author_' . $key, $author);
        }

        $manager->flush();
    }
}
