<?php

namespace App\Controller\Backend\Vehicle;

use App\Controller\Backend\BaseController;
use App\Entity\Vehicle\Operation;
use App\Form\Vehicle\OperationType;
use App\Repository\Vehicle\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/vehicle/operation")
 */
class OperationController extends BaseController
{
    /**
     * @Route("/", name="backend_vehicle_operation_index", methods={"GET"})
     */
    public function index(OperationRepository $operationRepository, Request $request): Response {
        $operations = $this->paginateWithSorting($operationRepository, $request);
        return $this->render('backend/vehicle/operation/index.html.twig', [
            'operations' => $operations,
        ]);
    }

    /**
     * @Route("/new", name="backend_vehicle_operation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $operation = new Operation();
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operation);
            $entityManager->flush();

            return $this->redirectToRoute('backend_vehicle_operation_index');
        }

        return $this->render('backend/vehicle/operation/new.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_vehicle_operation_show", methods={"GET"})
     */
    public function show(Operation $operation): Response
    {
        return $this->render('backend/vehicle/operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_vehicle_operation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Operation $operation): Response
    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_vehicle_operation_index', [
                'id' => $operation->getId(),
            ]);
        }

        return $this->render('backend/vehicle/operation/edit.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_vehicle_operation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Operation $operation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($operation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_vehicle_operation_index');
    }
}
