<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RouteLikeRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;

/**
 * @ORM\Entity(repositoryClass=RouteLikeRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"routes:read"}},
 *  collectionOperations={"get"},
 *  itemOperations={"get"}
 * )
 * @ApiFilter(NumericFilter::class, properties={"user.id"})
 */
class RouteLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"routes:read"})
     *
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="routeLikes")
     * @Groups({"routes:read"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="routeLikes")
     * @Groups({"routes:read"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $place;

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

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }

}
