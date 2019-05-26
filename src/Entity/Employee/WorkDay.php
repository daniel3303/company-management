<?php

namespace App\Entity\Employee;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Employee\WorkDayRepository")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"employee_id", "day"})})
 * @UniqueEntity(
 *     fields={"employee", "day"},
 *     message="Este dia de trabalho já está registado para este funcionário.",
 *     errorPath="day",
 * )
 */
class WorkDay {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="O dia é obrigatório")
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee\Employee", inversedBy="workDays")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="O funcionário é obrigatório.")
     */
    private $employee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Employee\WorkInterval", mappedBy="workDay", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"start" = "asc"})
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Deve adicionar pelo menos um período de trabalho.",
     * )
     * @Assert\Valid()
     */
    private $workIntervals;

    public function __construct() {
        $this->setDay(new \DateTime("now"));
        $this->workIntervals = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getDay(): ?\DateTimeInterface {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self {
        $this->day = $day;

        return $this;
    }

    public function getEmployee(): ?Employee {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self {
        $this->employee = $employee;

        foreach ($this->getWorkIntervals() as $interval) {
            if ($interval->getHourlyWage() === null) {
                $interval->setHourlyWage($employee->getHourlyWage());
            }
        }

        return $this;
    }

    public function getWorkingTime(): \DateTime {
        $time = new \DateTime('00:00');

        foreach ($this->getWorkIntervals() as $workInterval) {
            $time->add($workInterval->getDuration());
        }

        return $time;
    }

    public function getAmountToPay(): float {
        $total = 0.0;

        foreach ($this->getWorkIntervals() as $workInterval) {
            $total += $workInterval->getAmountToPay();
        }

        return $total;
    }

    public function getTotalHoursWorked(): float {
        $total = 0.0;

        foreach ($this->getWorkIntervals() as $workInterval) {
            $total += $workInterval->getHoursWorked();
        }

        return $total;
    }

    /**
     * @return Collection|WorkInterval[]
     */
    public function getWorkIntervals(): Collection {
        return $this->workIntervals;
    }

    public function addWorkInterval(WorkInterval $workInterval): self {
        if (!$this->workIntervals->contains($workInterval)) {
            $this->workIntervals[] = $workInterval;
            $workInterval->setWorkDay($this);
        }

        return $this;
    }

    public function removeWorkInterval(WorkInterval $workInterval): self {
        if ($this->workIntervals->contains($workInterval)) {
            $this->workIntervals->removeElement($workInterval);
            // set the owning side to null (unless already changed)
            if ($workInterval->getWorkDay() === $this) {
                $workInterval->setWorkDay(null);
            }
        }

        return $this;
    }
}
