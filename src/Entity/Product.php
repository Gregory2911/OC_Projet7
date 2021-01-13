<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"collection:product", "item:product"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)  
     * @Groups({"collection:product", "item:product"})
     * @Assert\NotBlank()
     * @Assert\Length(min="2")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"collection:product", "item:product"})
     * @Assert\NotBlank()
     * @Assert\Length(min="2")
     */
    private $brand;

    /**
     * @ORM\Column(type="text")
     * @Groups("item:product")
     * @Assert\NotBlank()
     * @Assert\Length(min="2")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("item:product")
     */
    private $screenSize;

    /**
     * @ORM\Column(type="date")
     * @Groups("item:product")
     * @Assert\NotBlank()
     * @Assert\Length(min="2")
     */
    private $releaseDate;

    /**
     * @Groups({"collection:product"})
     */
    private $links = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getScreenSize(): ?string
    {
        return $this->screenSize;
    }

    public function setScreenSize(?string $screenSize): self
    {
        $this->screenSize = $screenSize;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(?array $links): self
    {
        $this->links = $links;

        return $this;
    }
}
