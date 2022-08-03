<?php

namespace App\Entity;

use App\Repository\BookRentingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRentingRepository::class)]
class BookRenting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookRentings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bookRentings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $renting_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $renting_end = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $limit_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getRentingStart(): ?\DateTimeInterface
    {
        return $this->renting_start;
    }

    public function setRentingStart(\DateTimeInterface $renting_start): self
    {
        $this->renting_start = $renting_start;

        return $this;
    }

    public function getRentingEnd(): ?\DateTimeInterface
    {
        return $this->renting_end;
    }

    public function setRentingEnd(\DateTimeInterface $renting_end): self
    {
        $this->renting_end = $renting_end;

        return $this;
    }

    public function getLimitDate(): ?\DateTimeInterface
    {
        return $this->limit_date;
    }

    public function setLimitDate(\DateTimeInterface $limit_date): self
    {
        $this->limit_date = $limit_date;

        return $this;
    }

}