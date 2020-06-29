<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     itemOperations = { "get" },
 *     denormalizationContext = { "groups" = { "message:write" }}
 * )
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({ "message:write" })
     * @Assert\NotBlank(message="Gelieve een bericht in te vullen")
     * @Assert\Length(min=10, minMessage="He bericht moet minstens 10 karakters lang zijn.")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({ "message:write" })
     * @Assert\Length(min=8, minMessage="Telefoonnummer moet minstens 8 cijfers lang zijn.",
     *                max=20, maxMessage="Telefoonnummer kan maximaal 20 cijfers lang zijn.")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sentAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $answered;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({ "message:write" })
     * @Assert\NotBlank(message="Gelieve een emailadres in te vullen.")
     * @Assert\Email(message="Emailadres is ongeldig")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({ "message:write" })
     * @Assert\NotBlank(message="Gelieve je voornaam in te vullen.")
     * @Assert\Length(min=2, minMessage="Voornaam moet minstens 2 karakters lang zijn.",
     *                max=50, maxMessage="Voornaam kan maximaal 50 karakters lang zijn.")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({ "message:write" })
     * @Assert\NotBlank(message="Gelieve je achternaam in te vullen")
     * @Assert\Length(min=2, minMessage="Achternaam moet minstens 2 karakters lang zijn.",
     *                max=50, maxMessage="Achternaam kan maximaal 50 karakters lang zijn.")
     */
    private $lastName;

    /****************/
    /*   METHODES   */
    /****************/

    /** Bij het verzenden van een message word het moment opgeslagen
     *  answered staat standaard op false
     */
    public function __construct(){
        $this->sentAt = new \DateTimeImmutable('now');
        $this->answered = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getAnswered(): ?bool
    {
        return $this->answered;
    }

    public function setAnswered(bool $answered): self
    {
        $this->answered = $answered;
        return $this;
    }

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
    Geeft de id van een message terug*/
    public function __toString()
    {
        return (string) $this->id;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /** Genereerd de volledige naam van een sender */
    public function getName(){
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

}
