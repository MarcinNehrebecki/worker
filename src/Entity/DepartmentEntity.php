<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 * @ORM\Table(name="department")
 */
class DepartmentEntity
{
    const FIELD_PERCENT = 'Procent';
    const FIELD_SALARY = 'Kwota';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $bonusPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=WorkerEntity::class, mappedBy="department", orphanRemoval=true)
     */
    private $workers;

    public function __construct()
    {
        $this->workers = new ArrayCollection();
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

    public function getBonusPrice(): ?int
    {
        return $this->bonusPrice;
    }

    public function setBonusPrice(int $bonusPrice): self
    {
        $this->bonusPrice = $bonusPrice;

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

    /**
     * @return Collection|WorkerEntity[]
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }

    public function addWorker(WorkerEntity $worker): self
    {
        if (!$this->workers->contains($worker)) {
            $this->workers[] = $worker;
            $worker->setDepartment($this);
        }

        return $this;
    }

    public function removeWorker(WorkerEntity $worker): self
    {
        if ($this->workers->contains($worker)) {
            $this->workers->removeElement($worker);
            // set the owning side to null (unless already changed)
            if ($worker->getDepartment() === $this) {
                $worker->setDepartment(null);
            }
        }

        return $this;
    }
}
