<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AboutRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post" = { "security" = "is_granted('ROLE_ADMIN')" }
 *     },
 *     itemOperations={
 *          "get",
 *          "put" = { "security" = "is_granted('ROLE_ADMIN')" }
 *     },
 *     normalizationContext={"groups"={"about:read"}},
 *     denormalizationContext={"groups"={"admin:about:write"}},
 * )
 * @ORM\Entity(repositoryClass=AboutRepository::class)
 * @Vich\Uploadable()
 */
class About
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"about:read", "admin:about:write"})
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 100, maxMessage="TableName has to be between 2 and 100 chars")
     */
    private $tableName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"about:read", "admin:about:write"})
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100, maxMessage="Header has to be between 2 and 100 chars")
     */
    private $header;

    /**
     * @ORM\Column(type="text")
     * @Groups({"about:read", "admin:about:write"})
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="Message needs to be longer than 9 chars")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="about_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(string $header): self
    {
        $this->header = $header;

        return $this;
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

    public function __toString()
    {
        return (string) $this->header;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    public function setImageFile(File $image = null): void
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
