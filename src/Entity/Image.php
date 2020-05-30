<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post" = {"security" = "is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get",
 *          "put" = {"security" = "is_granted('ROLE_ADMIN')"},
 *          "delete" = { "security" = "is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"image:read"}},
 *     denormalizationContext={"groups"={"admin:image:write"}},
 * )
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups({ "admin:image:write", "image:read", "album:read", "album:item:read" })
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({ "admin:image:write", "image:read", "album:read", "album:item:read" })
     * @Assert\Length(min=10, minMessage="Message needs to be longer than 9 chars")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "admin:image:write", "image:read", "album:read", "album:item:read" })
     */
    private $alt;

//    /**
//     * @ORM\Column(type="integer")
//     * @Groups({ "admin:image:write" })
//     */
//    private $size;

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
     * @Groups({ "admin:image:write", "image:read", "album:read", "album:item:read" })
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "admin:image:write", "image:read", "album:read", "album:item:read" })
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="album_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    public function __construct()
    {
        $this->downloadLogs = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->uploadedAt = new \DateTimeImmutable(null , new \DateTimeZone('Europe/Brussels'));
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

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

//    public function getSize(): ?int
//    {
//        return $this->size;
//    }
//
//    public function setSize(int $size): self
//    {
//        $this->size = $size;
//
//        return $this;
//    }

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

        return $this;
    }

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

    public function __toString()
    {
        return (string) $this->image;
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
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            $album->removeImage($this);
        }

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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

}
