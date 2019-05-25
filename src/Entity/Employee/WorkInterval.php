<?php

namespace App\Entity\Employee;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Employee\WorkIntervalRepository")
 */
class WorkInterval
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $duration;

    /**
     * @ORM\Column(type="float")
     */
    private $hourlyWage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee\WorkDay", inversedBy="workIntervals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workDay;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getHourlyWage(): ?float
    {
        return $this->hourlyWage;
    }

    public function setHourlyWage(float $hourlyWage): self
    {
        $this->hourlyWage = $hourlyWage;

        return $this;
    }

    public function getWorkDay(): ?WorkDay
    {
        return $this->workDay;
    }

    public function setWorkDay(?WorkDay $workDay): self
    {
        $this->workDay = $workDay;

        return $this;
    }
}
