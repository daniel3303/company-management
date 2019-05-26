<?php

namespace App\Entity\Employee;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Employee\WorkIntervalRepository")
 */
class WorkInterval {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\NotNull(message="A hora de entrada é obrigatória.")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\NotNull(message="A hora de saída é obrigatória.")
     * @Assert\GreaterThan(message="O fim do intervalo de trabalho deve ser depois do início.", propertyPath="start")
     */
    private $end;


    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value=0, message="O valor por hora deve ser igual ou superior a {{ compared_value }}.")
     */
    private $hourlyWage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee\WorkDay", inversedBy="workIntervals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workDay;

    public function __construct() {

    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getStart(): ?\DateTime {
        return $this->start;
    }

    public function setStart(\DateTime $start): self {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTime {
        return $this->end;
    }

    public function setEnd(\DateTime $end): self {
        $this->end = $end;

        return $this;
    }

    public function getDuration(): \DateInterval {
        if($this->getStart() !== null && $this->getEnd() !== null) {
            return $this->getStart()->diff($this->getEnd());
        }else{
            return new \DateInterval();
        }
    }


    public function getAmountToPay() : float {
        return $this->getHoursWorked() * $this->getHourlyWage();
    }

    public function getHoursWorked() : float{
        $interval = (new \DateTime())->setTimestamp(0)->add($this->getDuration())->getTimestamp();
        $hours = $interval/(60*60);
        return $hours;
    }

    public function getHourlyWage(): ?float {
        return $this->hourlyWage;
    }

    public function setHourlyWage(?float $hourlyWage): self {
        $this->hourlyWage = $hourlyWage;

        return $this;
    }

    public function getWorkDay(): ?WorkDay {
        return $this->workDay;
    }

    public function setWorkDay(?WorkDay $workDay): self {
        $this->workDay = $workDay;

        return $this;
    }

    /**
     * @Assert\Callback()
     */
    public static function validate(WorkInterval $workInterval, ExecutionContextInterface $context, $payload) {
        $workDay = $workInterval->getWorkDay();

        // checks if the start of the interval is in the same day as the workDay
        if($workDay->getDay()->format("d-m-Y") !== $workInterval->getStart()->format("d-m-Y")){
            $context->buildViolation("O início do intervalo de trabalho deve ser em ".$workDay->getDay()->format("d-m-Y").".")
                ->atPath("start")->addViolation();
        }

        //checks if the working interval is less or equal to 24hours
        if($workInterval->getEnd()->getTimestamp() - $workInterval->getStart()->getTimestamp() > 24*60*60){
            $context->buildViolation("O intervalo de trabalho pode no máximo ter 24h.")
                ->atPath("end")->addViolation();
        }
    }
}
