<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get" = { "normalization_context" = { "groups" = { "contact:get" } } },
 *          "post" = {
 *              "security" = "is_granted('ROLE_ADMIN')" ,
 *              "denormalization_context" = { "groups" = { "admin:contact:write" } }
 *          }
 *     },
 *     itemOperations={
 *          "get" = { "normalization_context" = { "groups" = { "contact:get" } } },
 *          "put" = {
 *              "security" = "is_granted('ROLE_ADMIN')" ,
 *              "denormalization_context" = { "groups" = { "admin:contact:write" } }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({ "contact:get", "admin:contact:write"})
     */
    private $facebookLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({ "contact:get", "admin:contact:write"})
     */
    private $instagramLink;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({ "contact:get", "admin:contact:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({ "contact:get", "admin:contact:write"})
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({ "contact:get", "admin:contact:write"})
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): self
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->instagramLink;
    }

    public function setInstagramLink(?string $instagramLink): self
    {
        $this->instagramLink = $instagramLink;

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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
