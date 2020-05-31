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
 *          "get",
 *          "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "delete"
 *     },
 *     normalizationContext={"groups"={"like:read"}},
 *     denormalizationContext={"groups"={"like:write"}},
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
     * @Groups({ "like:read" })
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "like:read", "like:write", "user:item:read"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({ "like:read", "like:write"})
     */
    private $user;

    /****************/
    /*   METHODES   */
    /****************/


    public function __construct(){
        $this->date = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
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

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
    Geeft de id van een like terug*/
    public function __toString()
    {
        return (string) $this->id;
    }

}
