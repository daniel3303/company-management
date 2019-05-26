<?php

namespace App\Event;

use App\Entity\Employee\WorkDay;
use Symfony\Component\EventDispatcher\Event;

class WorkDayCreatedEvent extends Event {
    public const NAME = 'workday.created';

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