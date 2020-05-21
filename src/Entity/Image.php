<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"  = { "normalization_context" = { "groups" = { "image:read" } } },
 *          "post" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" = { "admin:image:write" } } }
 *          }
 *     },
 *     itemOperations={
 *          "get"  = { "normalization_context" = { "groups" = { "image:read" } } },
 *          "put" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" ={ "admin:image:write" } } }
 *          },
 *          "delete" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" ={ "admin:image:write" } } }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "admin:image:write", "image:read" })
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({ "admin:image:write", "image:read" })
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({ "admin:image:write", "image:read" })
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "admin:image:write", "image:read" })
     */
    private $alt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({ "admin:image:write" })
     */
    private $size;

    /**
     * @ORM\Column(type="datetime")
     */
    private $uploadedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=DownloadLog::class, mappedBy="image")
     */
    private $downloadLogs;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="image")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=AlbumImage::class, mappedBy="image")
     * @Groups({ "admin:image:write", "image:read" })
     */
    private $albumImages;

    public function __construct()
    {
        $this->downloadLogs = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->albumImages = new ArrayCollection();
        $this->uploadedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
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

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

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
            $albumImage->setImage($this);
        }

        return $this;
    }

    public function removeAlbumImage(AlbumImage $albumImage): self
    {
        if ($this->albumImages->contains($albumImage)) {
            $this->albumImages->removeElement($albumImage);
            // set the owning side to null (unless already changed)
            if ($albumImage->getImage() === $this) {
                $albumImage->setImage(null);
            }
        }

        return $this;
    }
}
