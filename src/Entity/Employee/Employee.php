<?php

namespace App\Entity\Employee;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
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
     * @ORM\OrderBy({"day" = "desc"})
     */
    private $workDays;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="O vencimento por hora extraordinária é obrigatório.")
     * @Assert\GreaterThanOrEqual(value=0, message="O vencimento por hora extraordinária deve ser igual ou superior a {{ compared_value }}€.")
     */
    private $extraHourlyWage;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="O número de horas de trabalho por dia é obrigatório.")
     * @Assert\GreaterThanOrEqual(value=0, message="O número de horas de trabalho por dia deve ser igual ou supeior a {{ compared_value }}€.")
     */
    private $workingHoursPerDay;

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

    public function getWorkDaysBetween(\DateTime $start, \DateTime $end) : Collection{
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gte('day', $start))
            ->andWhere(Criteria::expr()->lte('day', $end))
            ->orderBy(["day" => 'asc']);

        return $this->workDays->matching($criteria);
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

    public function getExtraHourlyWage(): ?float
    {
        return $this->extraHourlyWage;
    }

    public function setExtraHourlyWage(float $extraHourlyWage): self
    {
        $this->extraHourlyWage = $extraHourlyWage;

        return $this;
    }

    public function getWorkingHoursPerDay(): ?float
    {
        return $this->workingHoursPerDay;
    }

    public function setWorkingHoursPerDay(float $workingHoursPerDay): self
    {
        $this->workingHoursPerDay = $workingHoursPerDay;

        return $this;
    }
}
