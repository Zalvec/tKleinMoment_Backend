<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={ "get" },
 *     itemOperations={ "get" },
 *     normalizationContext = { "groups" = { "menu:read" } }
 * )
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"menu:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"menu:read"})
     */
    private $ordering;

    /****************/
    /*   METHODES   */
    /****************/

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): self
    {
        $this->ordering = $ordering;

        return $this;
    }

    /** Voor easyAdmin moet er van elke entiteit een string meegegeven worden.
    Geeft de naam van een menu terug*/
    public function __toString()
    {
        return (string) $this->name;
    }

}
