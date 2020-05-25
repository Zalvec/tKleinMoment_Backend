<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumTagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post" = {"security" = "is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get",
 *          "put" = {"security" = "is_granted('ROLE_ADMIN')"},
 *          "delete" = {"security" = "is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"albumtag:read"}},
 *     denormalizationContext={"groups"={"admin:albumtag:write"}},
 * )
 * @ORM\Entity(repositoryClass=AlbumTagRepository::class)
 */
class AlbumTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Album::class, inversedBy="albumTags")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "albumtag:read", "admin:albumtag:write" })
     */
    private $album;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class, inversedBy="albumTags")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "albumtag:read", "admin:albumtag:write", "album:item:read" })
     */
    private $tag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlbum(): ?album
    {
        return $this->album;
    }

    public function setAlbum(?album $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getTag(): ?tag
    {
        return $this->tag;
    }

    public function setTag(?tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

}
