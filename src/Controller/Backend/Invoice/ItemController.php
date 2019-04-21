<?php

namespace App\Controller\Backend\Invoice;

use App\Controller\Backend\BaseController;
use App\Entity\Invoice\Invoice;
use App\Entity\Invoice\Item;
use App\Form\Invoice\InvoiceType;
use App\Repository\Invoice\InvoiceRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/invoice/item")
 */
class ItemController extends BaseController {

    /**
     * @Route("/{id}", name="backend_invoice_item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Item $item): Response {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $item->getInvoice()->removeItem($item);

            if($item->getInvoice()->getItems()->isEmpty() && $item->getInvoice()->getPayments()->isEmpty()){
                $entityManager->remove($item->getInvoice());
            }
            $entityManager->flush();

        }

        return $this->redirectToRoute('backend_invoice_invoice_index');
    }
}

