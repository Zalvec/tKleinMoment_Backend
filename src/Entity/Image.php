<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Groups({ "admin:image:write", "image:read", "album:item:read" })
     * @Assert\NotBlank()
     */
    private $path;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({ "admin:image:write", "image:read", "album:item:read" })
     * @Assert\Length(min=10, minMessage="Message needs to be longer than 9 chars")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({ "admin:image:write", "image:read", "album:item:read" })
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
     * @ORM\OneToMany(targetEntity=DownloadLog::class, mappedBy="image")
     */
    private $downloadLogs;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="image")
     */
    private $likes;

    /**
     * @ORM\ManyToMany(targetEntity=Album::class, mappedBy="images")
     */
    private $albums;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function __construct()
    {
        $this->downloadLogs = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->uploadedAt = new \DateTimeImmutable();
        $this->albums = new ArrayCollection();
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
        return (string) $this->id;
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

}
