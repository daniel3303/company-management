<?php

namespace App\Controller\Backend\Vehicle;

use App\Controller\Backend\BaseController;
use App\Entity\Vehicle\OperationType;
use App\Form\Vehicle\OperationTypeType;
use App\Repository\Vehicle\OperationTypeRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/vehicle/operation/type")
 */
class OperationTypeController extends BaseController
{
    /**
     * @Route("/", name="backend_vehicle_operation_type_index", methods={"GET"})
     */
    public function index(OperationTypeRepository $operationTypeRepository, Request $request): Response {
        $operationTypes = $this->paginateWithSorting($operationTypeRepository, $request);
        /** @var $operationTypes OperationType[]|Pagerfanta */

        return $this->render('backend/vehicle/operation_type/index.html.twig', [
            'operationTypes' => $operationTypes,
        ]);
    }

    /**
     * @Route("/new", name="backend_vehicle_operation_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $operationType = new OperationType();
        $form = $this->createForm(OperationTypeType::class, $operationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operationType);
            $entityManager->flush();

            return $this->redirectToRoute('backend_vehicle_operation_type_index');
        }

        return $this->render('backend/vehicle/operation_type/new.html.twig', [
            'operationType' => $operationType,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="backend_vehicle_operation_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, OperationType $operationType): Response
    {
        $form = $this->createForm(OperationTypeType::class, $operationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_vehicle_operation_type_index', [
                'id' => $operationType->getId(),
            ]);
        }

        return $this->render('backend/vehicle/operation_type/edit.html.twig', [
            'operationType' => $operationType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_vehicle_operation_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, OperationType $operationType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operationType->getId(), $request->request->get('_token'))) {
            if($operationType->getOperations()->count() > 0){
                $this->addFlash("warning", "Impossível apagar este tipo de operação porque existem elementos associados.");
                return $this->redirectToRoute("backend_vehicle_operation_type_index");
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($operationType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_vehicle_operation_type_index');
    }
}
