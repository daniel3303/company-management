<?php

namespace App\Controller\Backend\Employee;

use App\Controller\Backend\BaseController;
use App\Entity\Employee\Employee;
use App\Form\Employee\EmployeeType;
use App\Repository\Employee\EmployeeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("backend/employees")
 */
class EmployeeController extends BaseController {
    /**
     * @Route("/", name="backend_employee_employee_index", methods={"GET"})
     */
    public function index(EmployeeRepository $employeeRepository, Request $request): Response {
        $employees = $this->paginateWithSorting($employeeRepository, $request);
        /** @var $invoices Employee[]|PaginatorInterface */

        return $this->render('backend/employee/employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    /**
     * @Route("/new", name="backend_employee_employee_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('backend_employee_employee_index');
        }

        return $this->render('backend/employee/employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_employee_employee_show", methods={"GET"})
     */
    public function show(Employee $employee): Response {
        return $this->render('backend/employee/employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_employee_employee_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Employee $employee): Response {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_employee_employee_index', [
                'id' => $employee->getId(),
            ]);
        }

        return $this->render('backend/employee/employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_employee_employee_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Employee $employee): Response {
        if ($this->isCsrfTokenValid('delete' . $employee->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($employee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_employee_employee_index');
    }
}
