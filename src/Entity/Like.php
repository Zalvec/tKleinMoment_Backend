<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//security="is_granted('ROLE_USER')",
/**
 * @ApiResource(
 *     collectionOperations={
 *          "get" = { "normalization_context" = { "groups" = { "like:get" } } },
 *          "post" = { "denormalization_context" = { "groups" = { "like:write" } } }
 *     },
 *     itemOperations={
 *          "get" = { "normalization_context" = { "groups" = { "like:get" } } },
 *          "delete" = { "denormalization_context" = { "groups" = { "like:write" } } }
 *     }
 * )
 * @ORM\Entity(repositoryClass=LikeRepository::class)
 * @ORM\Table(name="`like`")
 */
class Like
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "like:get", "like:write"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "like:get", "like:write"})
     */
    private $user;

    public function __construct(){
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
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
}
