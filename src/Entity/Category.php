<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: BookCategory::class)]
    private Collection $bookCategories;

    public function __construct()
    {
        $this->bookCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
            $bookCategory->setCategory($this);
        }

        return $this;
    }

    public function removeBookCategory(BookCategory $bookCategory): self
    {
        if ($this->bookCategories->removeElement($bookCategory)) {
            // set the owning side to null (unless already changed)
            if ($bookCategory->getCategory() === $this) {
                $bookCategory->setCategory(null);
            }
        }

        return $this;
    }
}
