<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(type: Types::SMALLINT)]
  private ?int $rating = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $content = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\ManyToOne(inversedBy: 'reviews')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Movie $movie = null;

  #[ORM\ManyToOne(inversedBy: 'reviews')]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $author = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getRating(): ?int
  {
    return $this->rating;
  }

  public function setRating(int $rating): static
  {
    $this->rating = $rating;

    return $this;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(?string $content): static
  {
    $this->content = $content;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getMovie(): ?Movie
  {
    return $this->movie;
  }

  public function setMovie(?Movie $movie): static
  {
    $this->movie = $movie;

    return $this;
  }

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): static
  {
    $this->author = $author;

    return $this;
  }
}
