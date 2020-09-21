<?php

namespace App\Entity;

use App\Repository\DelegationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DelegationRepository::class)
 */
class Delegation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDelegation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishDelegation;

    /**
     * @ORM\ManyToOne(targetEntity=DelegationCountry::class, inversedBy="delegation")
     */
    private $delegationCountry;

    /**
     * @ORM\ManyToOne(targetEntity=Employee::class, inversedBy="delegations")
     */
    private $employee;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFinish;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDelegation(): ?\DateTimeInterface
    {
        return $this->startDelegation;
    }

    public function setStartDelegation(\DateTimeInterface $startDelegation): self
    {
        $this->startDelegation = $startDelegation;

        return $this;
    }

    public function getFinishDelegation(): ?\DateTimeInterface
    {
        return $this->finishDelegation;
    }

    public function setFinishDelegation(?\DateTimeInterface $finishDelegation): self
    {
        $this->finishDelegation = $finishDelegation;

        return $this;
    }

    public function getDelegationCountry(): ?delegationCountry
    {
        return $this->delegationCountry;
    }

    public function setDelegationCountry(?delegationCountry $delegationCountry): self
    {
        $this->delegationCountry = $delegationCountry;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getIsFinish(): ?bool
    {
        return $this->isFinish;
    }

    public function setIsFinish(bool $isFinish): self
    {
        $this->isFinish = $isFinish;

        return $this;
    }
}
