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
 *     collectionOperations={ "get" },
 *     itemOperations={ "get" },
 *     normalizationContext={"groups"={"about:read"}},
 * )
 * @ORM\Entity(repositoryClass=AboutRepository::class)
 * @Vich\Uploadable()
 */
class About
{
    /**
     * @ORM\Id()
     * @Groups({"about:read"})
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"about:read"})
     * @Assert\NotBlank(message="Gelieve deze about een beschrijving of naam te geven die enkel voor u zichtbaar is.")
     * @Assert\Length(min = 2, minMessage="Beschrijving / naam moet minstens 2 karakters lang zijn.",
     *                max = 100, maxMessage="Beschrijving / naam kan maximaal 100 karakters lang zijn.")
     */
    private $tableName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"about:read"})
     * @Assert\NotBlank(message="Gelieve een titel in te vullen")
     * @Assert\Length(min=2, minMessage="Titel moet minstens 2 karakters lang zijn.",
     *                max=100, maxMessage="Titel mag maximaal 100 karakters lang zijn.")
     */
    private $header;

    /**
     * @ORM\Column(type="text")
     * @Groups({"about:read"})
     * @Assert\NotBlank(message="Gelieve een stuk tekst in te vullen.")
     * @Assert\Length(min=10, minMessage="Tekst moet minstens 10 karakters lang zijn.")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"about:read"})
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="about_images", fileNameProperty="image")
     * @Assert\File( maxSize="16M", maxSizeMessage="Uploadgrootte is maximaal 16MB.")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /****************/
    /*   METHODES   */
    /****************/

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    /** Als een image wordt opgeladen wordt,
     *  de naam van de image opgelagen
     *  de datum opgeslagen onder updated
     */
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

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
    Geeft de header terug*/
    public function __toString()
    {
        return (string) $this->header;
    }
}
