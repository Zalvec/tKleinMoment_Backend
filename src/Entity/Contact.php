<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={ "get" },
 *     itemOperations={ "get" },
 *     normalizationContext={"groups"={"contact:read"}},
 * )
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id()
     * @Groups({ "contact:read" })
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({ "contact:read" })
     */
    private $facebookLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({ "contact:read" })
     */
    private $instagramLink;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({ "contact:read" })
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({ "contact:read" })
     * @Assert\Length(max=20)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({ "contact:read" })
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /****************/
    /*   METHODES   */
    /****************/

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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
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

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
    Geeft de naam terug*/
    public function __toString()
    {
        return (string) $this->name;
    }

}
