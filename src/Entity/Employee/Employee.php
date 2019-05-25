<?php

namespace App\Entity\Employee;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Employee\EmployeeRepository")
 */
class Employee {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotNull(message="O nome é obrigatório.")
     * @Assert\Length(
     *     min="3",
     *     minMessage="O nome deve ter pelo menos 3 caracteres.",
     *     max="256",
     *     maxMessage="O nome deve ter no máximo 256 caracteres."
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="O vencimento por hora é obrigatório.")
     * @Assert\GreaterThanOrEqual(value=0, message="O vencimento por hora deve ser igual ou superior a {{ compared_value }}€.")
     */
    private $hourlyWage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Employee\WorkDay", mappedBy="employee", orphanRemoval=true)
     */
    private $workDays;

    public function __construct() {
        $this->workDays = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getHourlyWage(): ?float {
        return $this->hourlyWage;
    }

    public function setHourlyWage(float $hourlyWage): self {
        $this->hourlyWage = $hourlyWage;

        return $this;
    }

    /**
     * @return Collection|WorkDay[]
     */
    public function getWorkDays(): Collection {
        return $this->workDays;
    }

    public function addWorkDay(WorkDay $workDay): self {
        if (!$this->workDays->contains($workDay)) {
            $this->workDays[] = $workDay;
            $workDay->setEmployee($this);
        }

        return $this;
    }

    public function removeWorkDay(WorkDay $workDay): self {
        if ($this->workDays->contains($workDay)) {
            $this->workDays->removeElement($workDay);
            // set the owning side to null (unless already changed)
            if ($workDay->getEmployee() === $this) {
                $workDay->setEmployee(null);
            }
        }

        return $this;
    }
}
