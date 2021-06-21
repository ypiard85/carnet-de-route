<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlaceRepository;
use JMS\Serializer\Annotation\Exclude;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;
use JMS\Serializer\Annotation as JMS;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;


/**
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 * @Vich\Uploadable
 * @Fresh\VichSerializableClass
 * @ApiResource(
 *  normalizationContext={"groups"={"place:read"}},
 *  collectionOperations={"get"},
 *  itemOperations={"get"}
 * )
 * @ApiFilter(NumericFilter::class, properties={"places.id"})
 */
class Place
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"routes:read", "place:read" })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"routes:read", "place:read"})
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="places")
     * @Groups({"routes:read", "place:read"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $city;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="place", cascade={"persist"})
     * @var File|null
     *
     * @Vich\UploadableField(mapping="lieux_img", fileNameProperty="photoLieuName")
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"routes:read", "place:read"})
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"routes:read", "place:read"})
     */
    private $longs;

    /**
     * @ORM\OneToMany(targetEntity=RouteLike::class, mappedBy="place")
     */
    private $routeLikes;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="place")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="places")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->routeLikes = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

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

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPlace($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPlace() === $this) {
                $image->setPlace(null);
            }
        }

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLongs(): ?string
    {
        return $this->longs;
    }

    public function setLongs(string $longs): self
    {
        $this->longs = $longs;

        return $this;
    }

    /**
     * @return Collection|RouteLike[]
     */
    public function getRouteLikes(): Collection
    {
        return $this->routeLikes;
    }

    public function addRouteLike(RouteLike $routeLike): self
    {
        if (!$this->routeLikes->contains($routeLike)) {
            $this->routeLikes[] = $routeLike;
            $routeLike->setPlace($this);
        }

        return $this;
    }

    public function removeRouteLike(RouteLike $routeLike): self
    {
        if ($this->routeLikes->removeElement($routeLike)) {
            // set the owning side to null (unless already changed)
            if ($routeLike->getPlace() === $this) {
                $routeLike->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * Savoir si le lieux est ajouter par l'utilisateur
     */
    public function isRouteUser(User $user) : bool{
        foreach($this->routeLikes as $routelike){
            if($routelike->getUser() == $user ) return true;
        }

        return false;
    }

    /**
     * Savoir si le lieu est liker
     */
    public function isLike(User $user) : bool
    {
        foreach($this->likes as $likes){
            if($likes->getUser() == $user) return true;
        }

        return false;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPlace($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPlace() === $this) {
                $like->setPlace(null);
            }
        }

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }


}
