<?php

namespace App\Controller\Employee;

use App\Controller\Backend\BaseController;
use App\Entity\Employee\Employee;
use App\Entity\Employee\WorkDay;
use App\Event\WorkDayCreatedEvent;
use App\Event\WorkDayUpdatedEvent;
use App\Form\Employee\WorkDayType;
use App\Repository\Employee\EmployeeRepository;
use App\Repository\Employee\WorkDayRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("backend/employee/work/day")
 */
class WorkDayController extends BaseController {

    /**
     * @Route("/new", name="backend_employee_work_day_new", methods={"GET","POST"})
     */
    public function new(Request $request, EventDispatcherInterface $eventDispatcher): Response {
        $workDay = new WorkDay();
        $form = $this->createForm(WorkDayType::class, $workDay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($workDay);
            $eventDispatcher->dispatch(WorkDayCreatedEvent::NAME, new WorkDayCreatedEvent($workDay));
            $entityManager->flush();

            return $this->redirectToRoute('backend_employee_employee_show', ['id' => $workDay->getEmployee()->getId()]);
        }

        return $this->render('backend/employee/work_day/new.html.twig', [
            'workDay' => $workDay,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_employee_work_day_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WorkDay $workDay, EventDispatcherInterface $eventDispatcher): Response {
        $form = $this->createForm(WorkDayType::class, $workDay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventDispatcher->dispatch(WorkDayUpdatedEvent::NAME, new WorkDayUpdatedEvent($workDay));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_employee_employee_show', [
                'id' => $workDay->getEmployee()->getId(),
            ]);
        }

        return $this->render('backend/employee/work_day/edit.html.twig', [
            'workDay' => $workDay,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show", name="backend_employee_work_day_show", methods={"GET"})
     */
    public function show(Request $request): Response {
        $start = $request->get("start", null);
        $end = $request->get("end", null);

        if ($start !== null && $end !== null) {
            return $this->redirectToRoute("backend_employee_work_day_show_map", [
                'start' => $start,
                'end' => $end
            ]);
        }

        return $this->render('backend/employee/work_day/show.html.twig');
    }

    /**
     * @Route("/show/{start}/{end}", defaults={"start"=null, "end"=null}, name="backend_employee_work_day_show_map", methods={"GET"})
     */
    public function showMap(\DateTime $start, \DateTime $end, EmployeeRepository $employeeRepository): Response {
        $MAX_INTERVAL_DAYS = 90;

        /**
         * @var $employees Employee[]
         */
        $employees = $employeeRepository->findAllWithPaginator('name', 'asc');
        $end->setTime(0, 0, 1); //minor fix to make dateperiod include the end date

        // Checks if the interval is bigger than $MAX_INTERVAL_DAYS days
        if ($end->diff($start)->days > $MAX_INTERVAL_DAYS) {
            $end->setTimestamp($start->getTimestamp() + $MAX_INTERVAL_DAYS * 24 * 60 * 60);
            $this->addFlash("warning", "O periodo máximo de vizualização permitido é de " . $MAX_INTERVAL_DAYS . " dias.");
        }

        // Maps stats
        $hoursPerEmployee = [];
        foreach ($employees as $employee){
            $hoursPerEmployee[$employee->getId()] = [];
            $hours = &$hoursPerEmployee[$employee->getId()];
            $workDays = $employee->getWorkDaysBetween($start, $end);

            foreach ($workDays as $workDay){
                foreach ($workDay->getWorkIntervals() as $workInterval){
                    if(isset($hours[$workInterval->getHourlyWage()])){
                        $hours[$workInterval->getHourlyWage()] += $workInterval->getHoursWorked();
                    }else{
                        $hours[$workInterval->getHourlyWage()] = $workInterval->getHoursWorked();
                    }
                }
            }
        }

        return $this->render('backend/employee/work_day/show_map.html.twig', [
            'hoursPerEmployee' => $hoursPerEmployee,
            'employees' => $employees,
            'startDay' => $start,
            'endDay' => $end,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_employee_work_day_delete", methods={"DELETE"})
     */
    public function delete(Request $request, WorkDay $workDay): Response {
        if ($this->isCsrfTokenValid('delete' . $workDay->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($workDay);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_employee_employee_show', ['id' => $workDay->getEmployee()->getId()]);
    }
}
