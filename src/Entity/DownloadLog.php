<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DownloadLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get" = {"security" = "is_granted('ROLE_ADMIN')"},
 *          "post" = { "security" = "is_granted('ROLE_USER')" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('ROLE_ADMIN')" }
 *     },
 *     normalizationContext={"groups"={"admin:download:read"}},
 *     denormalizationContext={"groups"={"download:write"}},
 * )
 * @ORM\Entity(repositoryClass=DownloadLogRepository::class)
 */
class DownloadLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({ "admin:download:read" })
     */
    private $downloadedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="downloadLogs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "admin:download:read", "download:write" })
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="downloadLogs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "admin:download:read", "download:write" })
     */
    private $user;

    public function __construct(){
        $this->downloadedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDownloadedAt(): ?\DateTimeInterface
    {
        return $this->downloadedAt;
    }

    public function getImage(): ?image
    {
        return $this->image;
    }

    public function setImage(?image $image): self
    {
        $this->image = $image;

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

    public function __toString()
    {
        return (string) $this->id;
    }

}
