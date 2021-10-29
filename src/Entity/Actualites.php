<?php

namespace App\Entity;

use App\Repository\ActualitesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActualitesRepository::class)
 */
class Actualites
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=ActuImage::class, mappedBy="actualite", cascade={"all"})
     */
    private $actuImages;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->actuImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|ActuImage[]
     */
    public function getActuImages(): Collection
    {
        return $this->actuImages;
    }

    public function addActuImage(ActuImage $actuImage): self
    {
        if (!$this->actuImages->contains($actuImage)) {
            $this->actuImages[] = $actuImage;
            $actuImage->setActualite($this);
        }

        return $this;
    }

    public function removeActuImage(ActuImage $actuImage): self
    {
        if ($this->actuImages->removeElement($actuImage)) {
            // set the owning side to null (unless already changed)
            if ($actuImage->getActualite() === $this) {
                $actuImage->setActualite(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->actuImages;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
