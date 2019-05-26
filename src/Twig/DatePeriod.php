<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-05-26
 * Time: 17:54
 */

namespace App\Twig;


use DateInterval;
use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DatePeriod extends AbstractExtension {

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFunctions() {
        return array(
            new TwigFunction('datePeriod', array($this, 'datePeriodFilter')),
        );
    }

    /**
     * Creates a DatePeriod object.
     * It allows iteration over a set of dates and times, recurring at regular intervals, over a given period.
     *
     * @param DateTime $start The start date of the period
     * @param DateTime $end The end date of the period
     * @param DateInterval $interval The interval between recurrences within the period
     * @return array
     */
    public function datePeriodFilter($start, $end, $interval = '1 day'): \DatePeriod {
        $interval = DateInterval::createFromDateString($interval);
        $datePeriod = new \DatePeriod($start, $interval, $end);
        return $datePeriod;
    }
}