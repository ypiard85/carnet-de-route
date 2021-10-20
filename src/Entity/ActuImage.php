<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=ActuImageRepository::class)
 * @Vich\Uploadable
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
     *
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
