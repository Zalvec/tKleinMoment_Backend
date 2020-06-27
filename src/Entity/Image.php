<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"
 *     },
 *     itemOperations={
 *          "get"
 *     },
 *     normalizationContext={"groups"={"image:read"}},
 * )
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"active"})
 * @Vich\Uploadable()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups({ "image:read", "album:item:read" })
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({ "image:read", "album:item:read" })
     * @Assert\Length(min=10, minMessage="Bericht moet minstens 10 karakters lang zijn.")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({ "image:read", "album:item:read" })
     * @Assert\NotBlank(message="Een foto moet een alt beschrijving hebben.")
     * @Assert\Length(min="2", minMessage="De alt-beschrijving moet minstens 2 kakarters lang zijn.",
     *                max="150", maxMessage="De alt-beschrijving kan maximaal 150 karakters lang zijn.")
     */
    private $alt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploadedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=DownloadLog::class, mappedBy="image", cascade={"remove"})
     */
    private $downloadLogs;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="image", cascade={"remove"})
     */
    private $likes;

    /**
     * @ORM\ManyToMany(targetEntity=Album::class, mappedBy="images")
     */
    private $albums;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({ "image:read", "album:item:read" })
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "image:read", "album:item:read" })
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="album_images", fileNameProperty="image")
     * @Assert\File( maxSize="16M", maxSizeMessage="Uploadgrootte is maximaal 16MB.")
     * @var File
     */
    private $imageFile;

    /****************/
    /*   METHODES   */
    /****************/

    /** Moment van upload wordt opgeslagen
     *  Alle images die worden geüpload staan automatisch als active
     */
    public function __construct()
    {
        $this->downloadLogs = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->uploadedAt = new \DateTimeImmutable('now');
        $this->albums = new ArrayCollection();
        $this->active = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /** Als een image wordt opgeladen, wordt
     *  de naam van de image opgelagen
     *  de datum opgeslagen onder updated
     */
    public function setImageFile(File $image): void
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
    Geeft de naam van een image terug*/
    public function __toString()
    {
        return (string) $this->image;
    }

    /*******************/
    /*   COLLECTIONS   */
    /*******************/

    /**
     * @return Collection|DownloadLog[]
     */
    public function getDownloadLogs(): Collection
    {
        return $this->downloadLogs;
    }

    public function addDownloadLog(DownloadLog $downloadLog): self
    {
        if (!$this->downloadLogs->contains($downloadLog)) {
            $this->downloadLogs[] = $downloadLog;
            $downloadLog->setImage($this);
        }

        return $this;
    }

    public function removeDownloadLog(DownloadLog $downloadLog): self
    {
        if ($this->downloadLogs->contains($downloadLog)) {
            $this->downloadLogs->removeElement($downloadLog);
            // set the owning side to null (unless already changed)
            if ($downloadLog->getImage() === $this) {
                $downloadLog->setImage(null);
            }
        }

        return $this;
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
            $like->setImage($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getImage() === $this) {
                $like->setImage(null);
            }
        }

        return $this;
    }

     /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->addImage($this);
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            $album->removeImage($this);
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }
}
