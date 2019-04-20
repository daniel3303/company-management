<?php

namespace App\Controller\Backend\Company;

use App\Entity\Company\Company;
use App\Entity\User;
use App\Form\Company\CompanyType;
use App\Repository\Company\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/company/all")
 */
class CompanyController extends AbstractController {
    /**
     * @Route("/", name="backend_company_company_index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response {
        return $this->render('backend/company/company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backend_company_company_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $company->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('backend_company_company_index');
        }

        return $this->render('backend/company/company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_company_company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_company_company_index', [
                'id' => $company->getId(),
            ]);
        }

        return $this->render('backend/company/company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_company_company_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Company $company): Response {
        if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_company_company_index');
    }
}
