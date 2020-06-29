<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     collectionOperations={ "get" },
 *     itemOperations={
 *          "get" = { "normalization_context" = { "groups" = { "album:item:read" } } }
 *     },
 *     normalizationContext={"groups"={"album:read"}},
 * )
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"name":"partial", "location":"partial", "event":"partial", "date":"exact"})
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(OrderFilter::class, properties={ "date": "DESC" })
 * @Vich\Uploadable()
 */
class Album
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups({ "album:read", "album:item:read" })
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({ "album:read", "album:item:read" })
     * @Assert\NotBlank(message="Gelieve een naam in te vullen voor dit album.")
     * @Assert\Length(min=2, minMessage="Naam van een album moet minstens 2 karakters lang zijn.",
     *                max=150, maxMessage="Naam van een album kan maximaal 150 karakters lang zijn.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "album:read", "album:item:read" })
     * @Assert\NotBlank(message="Gelieve een beschrijving van de locatie in te vullen")
     * @Assert\Length(min=2, minMessage="Locatie moet minstens 2 karakters lang zijn.",
     *                max=255, maxMessage="Locatie kan maximaal 50 karakters lang zijn.")
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({ "album:read", "album:item:read" })
     * @Assert\NotBlank(message="Gelieve het evenement in te vullen.")
     * @Assert\Length(min=2, minMessage="Evenement moet minstens 2 karakters lang zijn.",
     *                max=150, maxMessage="Evenement kan maximaal 150 karakters lang zijn.")
     */
    private $event;

    /**
     * @ORM\Column(type="date")
     * @Groups({ "album:read", "album:item:read" })
     * @Assert\NotBlank(message="Gelieve een datum aan te duiden.")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     * @Groups({ "album:read", "album:item:read" })
     * @Assert\NotBlank(message="Gelieve een beschrijving in te vullen.")
     * @Assert\Length(min=10, minMessage="Beschrijving moet minstens 10 karakters lang zijn.")
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="albums")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class, inversedBy="albums", cascade={"persist"})
     * @Groups({ "album:read", "album:item:read" })
     */
    private $images;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({ "album:read", "album:item:read" })
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "album:read", "album:item:read" })
     */
    private $cover;

    /**
     * @Vich\UploadableField(mapping="album_covers", fileNameProperty="cover")
     * @Assert\File( maxSize="16M", maxSizeMessage="Uploadgrootte is maximaal 16MB.")
     * @var File
     */
    private $coverFile;

    /****************/
    /*   METHODES   */
    /****************/

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->active = true;           /** A new album is always set on active */
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
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(string $event): self
    {
        $this->event = $event;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable('now');

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getCoverFile()
    {
        return $this->coverFile;
    }

    /** Als een image wordt opgeladen, wordt
     *  de naam van de image opgelagen
     *  de datum opgeslagen onder updated
     */
    public function setCoverFile(File $cover): void
    {
        $this->coverFile = $cover;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($cover) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
       Geeft de naam van een album terug, 'No Albums' als er geen album is*/
    public function __toString()
    {
        if(!$this->name) return 'No Albums';
        return (string) $this->name;
    }

    /*******************/
    /*   COLLECTIONS   */
    /*******************/

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $this->updatedAt = new \DateTimeImmutable('now');
        }

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
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }
}
