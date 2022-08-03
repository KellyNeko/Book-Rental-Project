<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: BookRenting::class)]
    private Collection $bookRentings;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: BookCategory::class)]
    private Collection $bookCategories;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    public function __construct()
    {
        $this->bookRentings = new ArrayCollection();
        $this->bookCategories = new ArrayCollection();
    }


    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, BookRenting>
     */
    public function getBookRentings(): Collection
    {
        return $this->bookRentings;
    }

    public function addBookRenting(BookRenting $bookRenting): self
    {
        if (!$this->bookRentings->contains($bookRenting)) {
            $this->bookRentings->add($bookRenting);
            $bookRenting->setBook($this);
        }

        return $this;
    }

    public function removeBookRenting(BookRenting $bookRenting): self
    {
        if ($this->bookRentings->removeElement($bookRenting)) {
            // set the owning side to null (unless already changed)
            if ($bookRenting->getBook() === $this) {
                $bookRenting->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BookCategory>
     */
    public function getBookCategories(): Collection
    {
        return $this->bookCategories;
    }

    public function addBookCategory(BookCategory $bookCategory): self
    {
        if (!$this->bookCategories->contains($bookCategory)) {
            $this->bookCategories->add($bookCategory);
            $bookCategory->setBook($this);
        }

        return $this;
    }

    public function removeBookCategory(BookCategory $bookCategory): self
    {
        if ($this->bookCategories->removeElement($bookCategory)) {
            // set the owning side to null (unless already changed)
            if ($bookCategory->getBook() === $this) {
                $bookCategory->setBook(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
