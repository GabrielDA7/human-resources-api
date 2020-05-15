<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 * @ApiResource(
 *     normalizationContext = {
 *          "groups"={"read"},
 *          "enable_max_depth"=true
 *     },
 *     denormalizationContext = { "groups" = {"write"} },
 *     collectionOperations={
 *          "post" = {
 *              "security" = "is_granted('ROLE_RECRUITER')",
 *          },
 *          "get"
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
     * @MaxDepth(2)
     * @Groups({"read"})
     */
    private $applications;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(2)
     * @Groups({"read"})
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invitation", mappedBy="offer")
     * @MaxDepth(2)
     * @Groups({"read"})
     */
    private $invitations;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->invitations = new ArrayCollection();
    }

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



    public function getOwner(): ?user
    {
        return $this->owner;
    }

    public function setOwner(?user $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function removeApplicant(Invitation $applicant): self
    {
        if ($this->offers->contains($applicant)) {
            $this->offers->removeElement($applicant);
            // set the owning side to null (unless already changed)
            if ($applicant->getOffer() === $this) {
                $applicant->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invitation[]
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
            $invitation->setOffer($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->contains($invitation)) {
            $this->invitations->removeElement($invitation);
            // set the owning side to null (unless already changed)
            if ($invitation->getOffer() === $this) {
                $invitation->setOffer(null);
            }
        }

        return $this;
    }
}
