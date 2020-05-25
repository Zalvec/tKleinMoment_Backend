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
     * @ORM\OneToMany(targetEntity=AlbumTag::class, mappedBy="tag")
     * @Groups({ "admin:tag:write", "tag:read" })
     */
    private $albumTags;

    public function __construct()
    {
        $this->albumTags = new ArrayCollection();
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

    /**
     * @return Collection|AlbumTag[]
     */
    public function getAlbumTags(): Collection
    {
        return $this->albumTags;
    }

    public function addAlbumTag(AlbumTag $albumTag): self
    {
        if (!$this->albumTags->contains($albumTag)) {
            $this->albumTags[] = $albumTag;
            $albumTag->setTag($this);
        }

        return $this;
    }

    public function removeAlbumTag(AlbumTag $albumTag): self
    {
        if ($this->albumTags->contains($albumTag)) {
            $this->albumTags->removeElement($albumTag);
            // set the owning side to null (unless already changed)
            if ($albumTag->getTag() === $this) {
                $albumTag->setTag(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->description;
    }

}
