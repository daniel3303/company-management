<?php

namespace App\Event;

use App\Entity\Employee\WorkDay;
use Symfony\Contracts\EventDispatcher\Event;

class WorkDayCreatedEvent extends Event {
    /**
     * @var WorkDay
     */
    protected $workDay;

    public function __construct(WorkDay $workDay) {
        $this->workDay = $workDay;
    }

    public function getWorkDay(): WorkDay {
        return $this->workDay;
    }
}