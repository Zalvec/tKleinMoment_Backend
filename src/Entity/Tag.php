<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
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
 *          "delete" = {"security" = "is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"tag:read"}},
 *     denormalizationContext={"groups"={ "admin:tag:write" }},
 * )
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"description":"exact"})
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({ "admin:tag:write", "tag:read", "album:item:read" })
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Album::class, mappedBy="Tag", cascade={"persist"})
     * @Groups({ "tag:read", "album:item:read" })
     */
    private $albums;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString()
    {
        return (string) $this->description;
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
            $album->addTag($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            $album->removeTag($this);
        }

        return $this;
    }

}
