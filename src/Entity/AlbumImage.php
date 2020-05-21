<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get" = { "normalization_context" = { "groups" = { "albumimg:read" } } },
 *          "post" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" = { "admin:albumimg:write" } } }
 *          }
 *     },
 *     itemOperations={
 *          "get" = { "normalization_context" = { "groups" = { "albumimg:read" } } },
 *          "delete" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" ={ "admin:albumimg:write" } } }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=AlbumImageRepository::class)
 */
class AlbumImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="albumImages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups( { "albumimg:read", "admin:albumimg:write" } )
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Album::class, inversedBy="albumImages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups( { "albumimg:read", "admin:albumimg:write" } )
     */
    private $album;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAlbum(): ?album
    {
        return $this->album;
    }

    public function setAlbum(?album $album): self
    {
        $this->album = $album;

        return $this;
    }
}
