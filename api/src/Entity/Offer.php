<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 * @ApiResource(
 *     normalizationContext = { "groups" = {"read"} },
 *     denormalizationContext = { "groups" = {"write"} },
 *     collectionOperations={
 *          "post" = {
 *              "defaults"= { "owner" = "user" },
 *          }
 *     },
 *     itemOperations={
 *          "get",
 *          "put" = {
 *              "security" = "object.owner == user"
 *          },
 *          "patch" = {
 *              "security" = "object.owner == user"
 *          },
 *          "delete" = {
 *              "security" = "object.owner == user"
 *          }
 *     }
 * )
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $companyDescription;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read", "write"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="offer")
     * @Groups({"read"})
     */
    private $applications;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $owner;

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

    public function getCompanyDescription(): ?string
    {
        return $this->companyDescription;
    }

    public function setCompanyDescription(string $companyDescription): self
    {
        $this->companyDescription = $companyDescription;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getApplications(): ?Application
    {
        return $this->applications;
    }

    public function setApplications(?Application $applications): self
    {
        $this->applications = $applications;

        return $this;
    }

    public function getOwner(): ?user
    {
        return $this->owner;
    }

    public function setOwner(?user $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
