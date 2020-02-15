<?php

namespace App\Controller\Backend\Company;

use App\Component\Company\CheckingAccount\CheckingAccountService;
use App\Controller\Backend\BaseController;
use App\Entity\Company\Company;
use App\Form\Company\CheckingAccountType;
use App\Repository\Company\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/company/checking-account")
 */
class CheckingAccountController extends BaseController {
    /**
     * @Route("/{company}", name="backend_company_company_checking_account_index")
     * @param Company $company
     * @param Request $request
     * @param CheckingAccountService $checkingAccountService
     * @return Response
     */
    public function index(Company $company, Request $request, CheckingAccountService $checkingAccountService, CompanyRepository $companyRepository): Response {
        $form = $this->createForm(CheckingAccountType::class, null, [
            'clients' => $companyRepository->findClientsOf($company),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $client = $form->getNormData()['client'];
            $checkingAccount = $checkingAccountService->getCheckingAccountFor($company, $client);

            return $this->render('backend/company/company/checking_account/show.html.twig', [
                'checkingAccount' => $checkingAccount,
            ]);
        }


        return $this->render('backend/company/company/checking_account/index.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}
