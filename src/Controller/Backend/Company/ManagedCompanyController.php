<?php

namespace App\Controller\Backend\Company;

use App\Entity\Company\ManagedCompany;
use App\Form\Company\ManagedCompanyType;
use App\Repository\Company\CompanyRepository;
use App\Repository\Company\ManagedCompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("backend/company/own")
 */
class ManagedCompanyController extends AbstractController
{
    /**
     * @Route("/", name="backend_company_managed_company_index", methods={"GET"})
     */
    public function index(ManagedCompanyRepository $companyRepository): Response
    {
        return $this->render('backend/company/managed_company/index.html.twig', [
            'companies' => $companyRepository->findAllWithPaginator("name"),
        ]);
    }

    /**
     * @Route("/new", name="backend_company_managed_company_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $managedCompany = new ManagedCompany();
        $form = $this->createForm(ManagedCompanyType::class, $managedCompany);
        $form->handleRequest($request);
        $managedCompany->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($managedCompany);
            $entityManager->flush();

            return $this->redirectToRoute('backend_company_managed_company_index');
        }

        return $this->render('backend/company/managed_company/new.html.twig', [
            'company' => $managedCompany,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="backend_company_managed_company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ManagedCompany $managedCompany): Response
    {
        $form = $this->createForm(ManagedCompanyType::class, $managedCompany);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_company_managed_company_index', [
                'id' => $managedCompany->getId(),
            ]);
        }

        return $this->render('backend/company/managed_company/edit.html.twig', [
            'company' => $managedCompany,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_company_managed_company_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ManagedCompany $managedCompany): Response
    {
        if ($this->isCsrfTokenValid('delete'.$managedCompany->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($managedCompany);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_company_managed_company_index');
    }
}
