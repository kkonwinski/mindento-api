<?php

namespace App\Entity;

use App\Repository\DelegationCountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DelegationCountryRepository::class)
 */
class DelegationCountry
{
    /**
     * @Groups("delegation_country")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups("d")
     */
    private $country;

    /**
     * @ORM\Column(type="integer")
     * @Groups("d")
     */
    private $amountDoe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency;

    /**
     * @Groups("delegation_detail")
     * @ORM\OneToMany(targetEntity=Delegation::class, mappedBy="delegationCountry")
     */
    private $delegation;

    public function __construct()
    {
        $this->delegation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAmountDoe(): ?int
    {
        return $this->amountDoe;
    }

    public function setAmountDoe(int $amountDoe): self
    {
        $this->amountDoe = $amountDoe;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection|Delegation[]
     */
    public function getDelegation(): Collection
    {
        return $this->delegation;
    }

    public function addDelegation(Delegation $delegation): self
    {
        if (!$this->delegation->contains($delegation)) {
            $this->delegation[] = $delegation;
            $delegation->setDalegationCountry($this);
        }

        return $this;
    }

    public function removeDelegation(Delegation $delegation): self
    {
        if ($this->delegation->contains($delegation)) {
            $this->delegation->removeElement($delegation);
            // set the owning side to null (unless already changed)
            if ($delegation->getDalegationCountry() === $this) {
                $delegation->setDalegationCountry(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getCountry();

    }
}
