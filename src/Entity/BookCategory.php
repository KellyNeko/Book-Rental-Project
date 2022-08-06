<?php

namespace App\Entity;

use App\Repository\BookCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

//A Category of a book (link between Book and Category)
#[ORM\Entity(repositoryClass: BookCategoryRepository::class)]
class BookCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //A Category
    #[ORM\ManyToOne(inversedBy: 'bookCategories')]
    private ?Category $category = null;

    //A Book
    #[ORM\ManyToOne(inversedBy: 'bookCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

}
