<?php

namespace App\Entity;

use App\Repository\SujetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SujetRepository::class)
 */
class Sujet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sujets")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=ForumCategorie::class, inversedBy="sujets", cascade={"remove"})
     */
    public $categorie;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=SujetResponse::class, mappedBy="sujet", cascade={"remove"})
     */
    private $sujetResponses;

    public function __construct()
    {
        $this->sujetResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCategorie(): ?forumCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(?forumCategorie $categorie): self
    {
        $this->categorie = $categorie;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function __toString(): string
    {
       return $this->createdAt AND $this->title;

    }

    /**
     * @return Collection|SujetResponse[]
     */
    public function getSujetResponses(): Collection
    {
        return $this->sujetResponses;
    }

    public function addSujetResponse(SujetResponse $sujetResponse): self
    {
        if (!$this->sujetResponses->contains($sujetResponse)) {
            $this->sujetResponses[] = $sujetResponse;
            $sujetResponse->setSujet($this);
        }

        return $this;
    }

    public function removeSujetResponse(SujetResponse $sujetResponse): self
    {
        if ($this->sujetResponses->removeElement($sujetResponse)) {
            // set the owning side to null (unless already changed)
            if ($sujetResponse->getSujet() === $this) {
                $sujetResponse->setSujet(null);
            }
        }

        return $this;
    }
}
