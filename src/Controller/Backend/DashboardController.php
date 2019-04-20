<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Repository\Company\CompanyRepository;
use App\Repository\Invoice\InvoiceRepository;
use App\Repository\Payment\PaymentRepository;
use App\Repository\Product\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashboardController extends BaseController {

    /**
     * @Route("/dashboard", name="backend_dashboard")
     */
    public function index(CompanyRepository $companyRepository, InvoiceRepository $invoiceRepository,
                          ProductRepository $productRepository, PaymentRepository $paymentRepository,
                            PaginatorInterface $paginator, Request $request) {
        $numCompanies = $companyRepository->countAllCompanies($this->getUser());
        $numInvoices = $invoiceRepository->countAllInvoices();
        $numProducts = $productRepository->countAllProducts();
        $numPayments = $paymentRepository->countAllPayments();

        $popularCompaniesQuery = $companyRepository->findAllOrderedByNumberInvoices()->getQuery();
        $paginator = $paginator->paginate($popularCompaniesQuery);
        $popularCompanies = $this->configurePaginator($paginator, $request);


        return $this->render('backend/dashboard/index.html.twig', [
            'numCompanies' => $numCompanies,
            'numInvoices' => $numInvoices,
            'numProducts' => $numProducts,
            'numPayments' => $numPayments,
            'popularCompanies' => $popularCompanies,
        ]);
    }
}
