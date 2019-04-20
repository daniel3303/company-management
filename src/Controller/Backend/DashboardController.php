<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Repository\Company\CompanyRepository;
use App\Repository\Invoice\InvoiceRepository;
use App\Repository\Payment\PaymentRepository;
use App\Repository\Product\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashboardController extends AbstractController {

    /**
     * @Route("/dashboard", name="backend_dashboard")
     */
    public function index(CompanyRepository $companyRepository, InvoiceRepository $invoiceRepository, ProductRepository $productRepository, PaymentRepository $paymentRepository) {
        $numCompanies = $companyRepository->countAllCompanies($this->getUser());
        $numInvoices = $invoiceRepository->countAllInvoices();
        $numProducts = $productRepository->countAllProducts();
        $numPayments = $paymentRepository->countAllPayments();

        return $this->render('backend/dashboard/index.html.twig', [
            'numCompanies' => $numCompanies,
            'numInvoices' => $numInvoices,
            'numProducts' => $numProducts,
            'numPayments' => $numPayments,
        ]);
    }
}
