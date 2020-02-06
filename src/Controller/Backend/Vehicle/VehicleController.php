<?php

namespace App\Controller\Backend\Vehicle;

use App\Controller\Backend\BaseController;
use App\Entity\Vehicle\Vehicle;
use App\Form\Vehicle\VehicleType;
use App\Repository\Vehicle\VehicleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/vehicle/vehicle")
 */
class VehicleController extends BaseController
{
    /**
     * @Route("/", name="backend_vehicle_vehicle_index", methods={"GET"})
     */
    public function index(VehicleRepository $vehicleRepository, Request $request): Response {
        $vehicles = $this->paginateWithSorting($vehicleRepository, $request);
        /** @var $vehicles Vehicle[]|PaginationInterface */

        return $this->render('backend/vehicle/vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * @Route("/new", name="backend_vehicle_vehicle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->redirectToRoute('backend_vehicle_vehicle_index');
        }

        return $this->render('backend/vehicle/vehicle/new.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_vehicle_vehicle_show", methods={"GET"})
     */
    public function show(Vehicle $vehicle): Response
    {
        return $this->render('backend/vehicle/vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_vehicle_vehicle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vehicle $vehicle): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_vehicle_vehicle_index', [
                'id' => $vehicle->getId(),
            ]);
        }

        return $this->render('backend/vehicle/vehicle/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_vehicle_vehicle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vehicle $vehicle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_vehicle_vehicle_index');
    }
}
