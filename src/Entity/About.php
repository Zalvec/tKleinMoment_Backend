<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AboutRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=255)
     * @Groups({"about:read", "admin:about:write"})
     * @Assert\NotBlank()
     */
    private $imagePath;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"about:read", "admin:about:write"})
     * @Assert\NotBlank()
     */
    private $imageName;

    /**
     * @ORM\Column(type="text")
     * @Groups({"about:read", "admin:about:write"})
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="Message needs to be longer than 9 chars")
     */
    private $text;

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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

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

}
