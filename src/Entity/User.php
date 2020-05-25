<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
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
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

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
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"cosplayName"})
 * @ApiFilter(PropertyFilter::class)
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
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write"})
     * @Assert\NotBlank()
     * @Assert\Email()
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
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write"})
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write"})
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true, unique=true)
     * @Groups({"user:read", "user:write", "user:item:read", "user:item:write"})
     * @Assert\Length(min=2, max=100)
     */
    private $cosplayName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @SerializedName("password")
     * @Groups({"user:write", "user:item:write"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"user:write", "user:item:read"})
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=DownloadLog::class, mappedBy="user")
     * @Groups({"user:read","user:write"})
     */
    private $downloadLogs;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="user")
     * @Groups({"user:read","user:write", "user:item:read"})
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    private $sendMessages;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private $receiverMessages;

    /**
     * @ORM\OneToMany(targetEntity=Album::class, mappedBy="user")
     */
    private $albums;

    public function __construct()
    {
        $this->downloadLogs = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->sendMessages = new ArrayCollection();
        $this->receiverMessages = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->roles[] = 'ROLE_USER';
        $this->createdAt = new \DateTimeImmutable();
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
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
//        if ($this->cosplayName) return (string) $this->cosplayName;
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
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCosplayName(): ?string
    {
        return $this->cosplayName;
    }

    public function setCosplayName(?string $cosplayName): self
    {
        $this->cosplayName = $cosplayName;
        $this->updatedAt = new \DateTimeImmutable();
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
        $this->updatedAt = new \DateTimeImmutable();
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): string
    {
//        if ($this->deletedAt){
//            $date = $this->deletedAt;
//            return date("d-m-Y", $date);
//        }
//        return "User is not deleted.";
        $this->deletedAt = $deletedAt;
        return $this;
    }

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
     * @return Collection|Message[]
     */
    public function getSendMessages(): Collection
    {
        return $this->sendMessages;
    }

    public function addSendMessage(Message $sendMessage): self
    {
        if (!$this->sendMessages->contains($sendMessage)) {
            $this->sendMessages[] = $sendMessage;
            $sendMessage->setSender($this);
        }

        return $this;
    }

    public function removeSendMessage(Message $sendMessage): self
    {
        if ($this->sendMessages->contains($sendMessage)) {
            $this->sendMessages->removeElement($sendMessage);
            // set the owning side to null (unless already changed)
            if ($sendMessage->getSender() === $this) {
                $sendMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceiverMessages(): Collection
    {
        return $this->receiverMessages;
    }

    public function addReceiverMessage(Message $receiverMessage): self
    {
        if (!$this->receiverMessages->contains($receiverMessage)) {
            $this->receiverMessages[] = $receiverMessage;
            $receiverMessage->setReceiver($this);
        }

        return $this;
    }

    public function removeReceiverMessage(Message $receiverMessage): self
    {
        if ($this->receiverMessages->contains($receiverMessage)) {
            $this->receiverMessages->removeElement($receiverMessage);
            // set the owning side to null (unless already changed)
            if ($receiverMessage->getReceiver() === $this) {
                $receiverMessage->setReceiver(null);
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
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->email;
    }

    public function getName(){
        return $this->getFirstName() . ' ' . $this->getLastname();
    }
}
