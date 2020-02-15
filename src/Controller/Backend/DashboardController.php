<?php

namespace App\Controller\Backend;

use App\Entity\Company\Company;
use App\Entity\User;
use App\Repository\Company\CompanyRepository;
use App\Repository\Company\ManagedCompanyRepository;
use App\Repository\Invoice\InvoiceRepository;
use App\Repository\Payment\PaymentRepository;
use App\Repository\Product\ProductRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashboardController extends BaseController {

    /**
     * @Route("/dashboard", name="backend_dashboard")
     */
    public function index(ManagedCompanyRepository $managedCompanyRepository, CompanyRepository $companyRepository, InvoiceRepository $invoiceRepository,
                          ProductRepository $productRepository, PaymentRepository $paymentRepository,
                          Request $request) {


        $numCompanies = $companyRepository->countAllCompanies();
        $numInvoices = $invoiceRepository->countAllInvoices();
        $numProducts = $productRepository->countAllProducts();
        $numPayments = $paymentRepository->countAllPayments();

        $myCompanies = $managedCompanyRepository->findAllWithPaginator('name', 'asc')->getQuery();
        $myCompanies = $this->paginate($myCompanies, $request);
        /** @var $myCompanies Company[]|PaginationInterface*/


        return $this->render('backend/dashboard/index.html.twig', [
            'numCompanies' => $numCompanies,
            'numInvoices' => $numInvoices,
            'numProducts' => $numProducts,
            'numPayments' => $numPayments,
            'myCompanies' => $myCompanies,
        ]);
    }
}
