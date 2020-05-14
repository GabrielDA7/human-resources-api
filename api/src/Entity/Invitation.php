<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationRepository")
 * @ApiResource(
 *     normalizationContext = {
 *          "groups"={"read"},
 *          "enable_max_depth"=true
 *     },
 *     denormalizationContext = { "groups" = {"write"} }
 * )
 */
class Invitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="invitations")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     * @Groups({"read", "write"})
     */
    private $offer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="invitations")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     * @Groups({"read", "write"})
     */
    private $applicant;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $token;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffer(): ?offer
    {
        return $this->offer;
    }

    public function setOffer(?offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getApplicant(): ?user
    {
        return $this->applicant;
    }

    public function setApplicant(?user $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
