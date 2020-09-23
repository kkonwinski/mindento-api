<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 */
class Employee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\OneToMany(targetEntity=Delegation::class, mappedBy="employee")
     */
    private $delegations;

    public function __construct()
    {
        $this->delegations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Delegation[]
     */
    public function getDelegations(): Collection
    {

        return $this->delegations;
    }

    public function addDelegation(Delegation $delegation): self
    {
        if (!$this->delegations->contains($delegation)) {
            $this->delegations[] = $delegation;
            $delegation->setEmployee($this);
        }

        return $this;
    }

    public function removeDelegation(Delegation $delegation): self
    {
        if ($this->delegations->contains($delegation)) {
            $this->delegations->removeElement($delegation);
            // set the owning side to null (unless already changed)
            if ($delegation->getEmployee() === $this) {
                $delegation->setEmployee(null);
            }
        }

        return $this;
    }
}
