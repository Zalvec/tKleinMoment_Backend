<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AboutRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get" = { "normalization_context" = { "groups" = { "about:get" } } },
 *          "post" = {
 *              "security" = "is_granted('ROLE_ADMIN')" ,
 *              "denormalization_context" = { "groups" = { "admin:about:write" } }
 *          }
 *     },
 *     itemOperations={
 *          "get" = { "normalization_context" = { "groups" = { "about:get" } } },
 *          "put" = {
 *              "security" = "is_granted('ROLE_ADMIN')" ,
 *              "denormalization_context" = { "groups" = { "admin:about:write" } }
 *          }
 *     }
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
     * @Groups({"about:get", "admin:about:write"})
     */
    private $tableName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"about:get", "admin:about:write"})
     */
    private $header;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"about:get", "admin:about:write"})
     */
    private $imagePath;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"about:get", "admin:about:write"})
     */
    private $imageName;

    /**
     * @ORM\Column(type="text")
     * @Groups({"about:get", "admin:about:write"})
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
}
