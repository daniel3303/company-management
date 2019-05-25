<?php

namespace App\Entity\Employee;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Employee\WorkDayRepository")
 */
class WorkDay
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee\Employee", inversedBy="workDays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Employee\WorkInterval", mappedBy="workDay", orphanRemoval=true)
     */
    private $workIntervals;

    public function __construct()
    {
        $this->workIntervals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

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

    /**
     * @return Collection|WorkInterval[]
     */
    public function getWorkIntervals(): Collection
    {
        return $this->workIntervals;
    }

    public function addWorkInterval(WorkInterval $workInterval): self
    {
        if (!$this->workIntervals->contains($workInterval)) {
            $this->workIntervals[] = $workInterval;
            $workInterval->setWorkDay($this);
        }

        return $this;
    }

    public function removeWorkInterval(WorkInterval $workInterval): self
    {
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
