<?php

namespace App\Controller\Backend\Invoice;

use App\Controller\Backend\BaseController;
use App\Entity\Invoice\Invoice;
use App\Form\Invoice\InvoiceType;
use App\Repository\Company\CompanyRepository;
use App\Repository\Invoice\InvoiceRepository;
use App\Repository\Product\ProductRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/invoice")
 */
class InvoiceController extends BaseController {
    /**
     * @Route("/", name="backend_invoice_invoice_index", methods={"GET"})
     */
    public function index(InvoiceRepository $invoiceRepository, ProductRepository $productRepository, CompanyRepository $companyRepository, Request $request): Response {
        $invoices = $this->paginateWithSorting($invoiceRepository, $request);
        /** @var $invoices Invoice[]|PaginatorInterface */


        return $this->render('backend/invoice/invoice/index.html.twig', [
            'invoices' => $invoices,
            'products' => $productRepository->findAll(),
            'companies' => $companyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="backend_invoice_invoice_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('backend_invoice_invoice_index');
        }

        return $this->render('backend/invoice/invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="backend_invoice_invoice_show", methods={"GET"})
     */
    public function show(Request $request, Invoice $invoice): Response {
        return $this->render('backend/invoice/invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="backend_invoice_invoice_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Invoice $invoice): Response {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_invoice_invoice_index', [
                'id' => $invoice->getId(),
            ]);
        }

        return $this->render('backend/invoice/invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_invoice_invoice_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Invoice $invoice): Response {
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_invoice_invoice_index');
    }
}
