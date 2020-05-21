<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AlbumTagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get" = { "normalization_context" = { "groups" = { "albumtag:read" } } },
 *          "post" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" = { "admin:albumtag:write" } } }
 *          }
 *     },
 *     itemOperations={
 *          "get" = { "normalization_context" = { "groups" = { "albumtag:read" } } },
 *          "put" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" = { "admin:albumtag:write" } } }
 *          },
 *          "delete" = {
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              { "denormalizationContext" = { "groups" ={ "admin:albumtag:write" } } }
 *          }
 *     }
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
     * @Groups({ "albumtag:read", "admin:albumtag:write" })
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
}
