<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     itemOperations={
 *          "get" = {"normalization_context"={"groups"={"user:item:read"}}},
 *          "put" = {
 *              "security" = "is_granted('ROLE_USER') and object == user",
 *              "denormalization_context" = { "groups" = { "user:item:write" }}
 *           },
 *          "delete" = { "security" = "is_granted('ROLE_USER') and object == user" }
 *     },
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Er bestaat reeds een gebruiker met dit emailadres.")
 * @UniqueEntity(fields={"cosplayName"}, message="Er bestaat reeds een gebruiker met deze cosplay naam.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "user:item:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write", "message:write"})
     * @Assert\NotBlank(message="Gelieve een geldig emailadres in te geven.")
     * @Assert\Email(message="Gelieve een geldig emailadres in te geven.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read","user:item:read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write", "message:write"})
     * @Assert\NotBlank(message="Gelieve je voornaam in te vullen")
     * @Assert\Length(min=2, minMessage="Voornaam moet minstens 2 karakters lang zijn.",
     *                max=50, maxMessage="Voornaam kan maximaal 50 karakters lang zijn.")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write", "message:write"})
     * @Assert\NotBlank(message="Gelieve je achternaam in te vullen")
     * @Assert\Length(min=2, minMessage="Achternaam moet minstens 2 karakters lang zijn.",
     *                max=50, maxMessage="Achternaam kan maximaal 50 karakters lang zijn.")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true, unique=true)
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write"})
     * @Assert\Length(min=2, minMessage="Cosplay naam moet minstens 2 karakters lang zijn.",
     *                max=50, maxMessage="Cosplay naam kan maximaal 50 karakters lang zijn.")
     */
    private $cosplayName;

    /**
     * @SerializedName("password")
     * @Groups({"user:write", "user:item:write"})
     * @Assert\Length(min="8", minMessage="Je wachtwoord moet minstens 8 karakters lang zijn.")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({ "user:item:read"})
     */
    private $regkey;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user:read", "user:item:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=DownloadLog::class, mappedBy="user", cascade={"remove"})
     * @Groups({ "user:read" })
     */
    private $downloadLogs;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="user", cascade={"remove"})
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Album::class, mappedBy="user")
     */
    private $albums;

    /****************/
    /*   METHODES   */
    /****************/

    /** Bij het aanmaken van een user worden volgende zaken automatisch ingesteld
     *  - createdAt is het moment van aanmaken
     *  - de roles van een user staan standaard op 'ROLE_USER'
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->downloadLogs = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->sendMessages = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->roles[] = 'ROLE_USER';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
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

    public function setlastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCosplayName(): ?string
    {
        return $this->cosplayName;
    }

    public function setCosplayName(?string $cosplayName): self
    {
        $this->cosplayName = $cosplayName;
        $this->updatedAt = new \DateTimeImmutable('now');

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getRegkey(): ?string
    {
        return $this->regkey;
    }

    public function setRegkey(string $regkey): self
    {
        $this->regkey = $regkey;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string|null
     * @Groups({"user:item:read"})
     */
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
//        if ($this->updatedAt){
//            $date = $this->updatedAt;
//            return date("d-m-Y", $date);
//        }
//        return "User is not updated yet.";
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
    Geeft de email van een user terug*/
    public function __toString(){
        return $this->email;
    }

    /** Genereerd de volledige naam van een user */
    public function getName(){
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /*******************/
    /*   COLLECTIONS   */
    /*******************/

    /**
     * @return Collection|DownloadLog[]
     */
    public function getDownloadLogs(): Collection
    {
        return $this->downloadLogs;
    }

    public function addDownloadLog(DownloadLog $downloadLog): self
    {
        if (!$this->downloadLogs->contains($downloadLog)) {
            $this->downloadLogs[] = $downloadLog;
            $downloadLog->setUser($this);
        }

        return $this;
    }

    public function removeDownloadLog(DownloadLog $downloadLog): self
    {
        if ($this->downloadLogs->contains($downloadLog)) {
            $this->downloadLogs->removeElement($downloadLog);
            // set the owning side to null (unless already changed)
            if ($downloadLog->getUser() === $this) {
                $downloadLog->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
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
            $album->setUser($this);
            $this->updatedAt = new \DateTimeImmutable('now');
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            // set the owning side to null (unless already changed)
            if ($album->getUser() === $this) {
                $album->setUser(null);
                $this->updatedAt = new \DateTimeImmutable('now');
            }
        }

        return $this;
    }
}
