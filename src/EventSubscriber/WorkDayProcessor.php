<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-28
 * Time: 03:28
 */

namespace App\EventSubscriber;

use App\Entity\Employee\WorkDay;
use App\Entity\Employee\WorkInterval;
use App\Event\WorkDayCreatedEvent;
use App\Event\WorkDayUpdatedEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Looks for changes in the following meta-parameters and stores changes in the user's session
 * - per-page
 * Class MetaParameterSubscriber
 * @package App\EventSubscriber
 */
class WorkDayProcessor implements EventSubscriberInterface {
    public function __construct() {
    }


    public static function getSubscribedEvents() {
        return [
            WorkDayCreatedEvent::class => 'onWorkDayCreated',
            WorkDayUpdatedEvent::class => 'onWorkDayUpdated',
        ];
    }

    public function processWorkDay(WorkDay $workDay) {
        $workIntervals = $this->getWorkIntervalsSortedByStartDate($workDay);
        $employ = $workDay->getEmployee();
        $normalHours = $workDay->getEmployee()->getWorkingHoursPerDay();

        $totalHours = 0.0;
        foreach ($workIntervals as $workInterval) {
            $afterHours = $totalHours + $workInterval->getHoursWorked();
            //If the price per hour is set ignore
            if ($workInterval->getHourlyWage() !== null) {
                continue;
            }

            if ($totalHours >= $normalHours && $afterHours >= $normalHours) {
                $workInterval->setHourlyWage($employ->getExtraHourlyWage());
            } else if ($totalHours <= $normalHours && $afterHours > $normalHours) {
                //Must split the interval
                $oldEnd = $workInterval->getEnd();
                $remainingNormalTime = intval(($normalHours - $totalHours)*60*60);
                $remainingNormalInterval =  new \DateInterval('PT'.$remainingNormalTime.'S');
                $finalDateTime = (clone $workInterval->getStart())->add($remainingNormalInterval);
                $workInterval->setEnd($finalDateTime);
                $workInterval->setHourlyWage($employ->getHourlyWage());

                //Extra hour interval
                $newWorkInterval = new WorkInterval();
                $newWorkInterval->setHourlyWage($employ->getExtraHourlyWage());
                $newWorkInterval->setStart(clone $workInterval->getEnd());
                $newWorkInterval->setEnd($oldEnd);
                $workDay->addWorkInterval($newWorkInterval);


            } else {
                $workInterval->setHourlyWage($employ->getHourlyWage());
            }


            $totalHours += $workInterval->getHoursWorked();

        }
    }

    /**
     * @param WorkDay $workDay
     * @return WorkInterval[]|ArrayCollection
     */
    public function getWorkIntervalsSortedByStartDate(WorkDay $workDay): ArrayCollection {
        $array = $workDay->getWorkIntervals()->getValues();
        usort($array, function (WorkInterval $a, WorkInterval $b){
            return ($a->getStart() < $b->getStart()) ? -1 : 1;
        });

        $collection = new ArrayCollection($array);
        return $collection;
    }

    public function onWorkDayCreated(WorkDayCreatedEvent $event) {
        $this->processWorkDay($event->getWorkDay());
    }

    public function onWorkDayUpdated(WorkDayUpdatedEvent $event) {
        $this->processWorkDay($event->getWorkDay());
    }

}