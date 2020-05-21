<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get" = { "normalization_context" = { "groups" = { "album:read" } } },
 *          "post" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalization_context" = { "groups" = { "admin:album:write" } } }
 *          }
 *     },
 *     itemOperations={
 *          "get" = { "normalization_context" = { "groups" = { "album:read" } } },
 *          "put" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalization_context" = { "groups" = { "admin:album:write" } } }
 *          },
 *          "delete" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalization_context" = { "groups" = { "admin:album:write" } } }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "album:read", "admin:album:write" })
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "album:read", "admin:album:write" })
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "album:read", "admin:album:write" })
     */
    private $event;

    /**
     * @ORM\Column(type="date")
     * @Groups({ "album:read", "admin:album:write" })
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     * @Groups({ "album:read", "admin:album:write" })
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=AlbumImage::class, mappedBy="album")
     */
    private $albumImages;

    /**
     * @ORM\OneToMany(targetEntity=AlbumTag::class, mappedBy="album")
     */
    private $albumTags;

    public function __construct()
    {
        $this->albumImages = new ArrayCollection();
        $this->albumTags = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

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

    /**
     * @return Collection|AlbumImage[]
     */
    public function getAlbumImages(): Collection
    {
        return $this->albumImages;
    }

    public function addAlbumImage(AlbumImage $albumImage): self
    {
        if (!$this->albumImages->contains($albumImage)) {
            $this->albumImages[] = $albumImage;
            $albumImage->setAlbum($this);
        }

        return $this;
    }

    public function removeAlbumImage(AlbumImage $albumImage): self
    {
        if ($this->albumImages->contains($albumImage)) {
            $this->albumImages->removeElement($albumImage);
            // set the owning side to null (unless already changed)
            if ($albumImage->getAlbum() === $this) {
                $albumImage->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AlbumTag[]
     */
    public function getAlbumTags(): Collection
    {
        return $this->albumTags;
    }

    public function addAlbumTag(AlbumTag $albumTag): self
    {
        if (!$this->albumTags->contains($albumTag)) {
            $this->albumTags[] = $albumTag;
            $albumTag->setAlbum($this);
        }

        return $this;
    }

    public function removeAlbumTag(AlbumTag $albumTag): self
    {
        if ($this->albumTags->contains($albumTag)) {
            $this->albumTags->removeElement($albumTag);
            // set the owning side to null (unless already changed)
            if ($albumTag->getAlbum() === $this) {
                $albumTag->setAlbum(null);
            }
        }

        return $this;
    }
}
