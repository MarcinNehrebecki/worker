<?php

namespace App\Entity;

use App\Repository\WorkerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkerRepository::class)
 * @ORM\Table(name="worker")
 */
class WorkerEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     */
    private $salary;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEmployment;

    /**
     * @ORM\ManyToOne(targetEntity=DepartmentEntity::class, inversedBy="workers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $department;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getDateEmployment(): ?\DateTimeInterface
    {
        return $this->dateEmployment;
    }

    public function setDateEmployment(\DateTimeInterface $dateEmployment): self
    {
        $this->dateEmployment = $dateEmployment;

        return $this;
    }

    public function getDepartment(): ?DepartmentEntity
    {
        return $this->department;
    }

    public function setDepartment(?DepartmentEntity $department): self
    {
        $this->department = $department;

        return $this;
    }
}
