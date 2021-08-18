<?php

namespace App\Entity;

use App\Repository\ActuImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActuImageRepository::class)
 */
class ActuImage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Actualites::class, inversedBy="actuImages", cascade={"all"})
     */
    private $actualite;

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

    public function getActualite(): ?Actualites
    {
        return $this->actualite;
    }

    public function setActualite(?Actualites $actualite): self
    {
        $this->actualite = $actualite;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
