<?php

namespace App\Entity;

use App\Repository\DelegationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=DelegationRepository::class)
 */
class Delegation
{
    public function __construct()
    {
        $this->setIsFinish(true);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Groups("d")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank
     * @Groups("d")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity=DelegationCountry::class, inversedBy="delegation")
     * @Assert\NotBlank
     * @Groups("d")
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

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }


    /**
     * @param string $time
     * @return \DateTime
     */
    public function createDateTimeFormat(string $time): \DateTime
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', $time);
    }

    public function getDelegationCountry(): ?DelegationCountry
    {
        return $this->delegationCountry;
    }

    public function setDelegationCountry(?DelegationCountry $delegationCountry): self
    {
        $this->delegationCountry = $delegationCountry;

        return $this;
    }


}
