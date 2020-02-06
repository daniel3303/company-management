<?php

namespace App\Controller\Backend\Invoice;

use App\Controller\Backend\BaseController;
use App\Entity\Invoice\Item;
use App\Repository\Company\CompanyRepository;
use App\Repository\Invoice\ItemRepository;
use App\Repository\Product\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/invoice/item")
 */
class ItemController extends BaseController {
    /**
     * @Route("/", name="backend_invoice_item_index", methods={"GET"})
     */
    public function index(ItemRepository $itemRepository, ProductRepository $productRepository, CompanyRepository $companyRepository, Request $request): Response {
        $items = $this->paginateWithSorting($itemRepository, $request);
        /** @var $items Item[]|PaginatorInterface */


        return $this->render('backend/invoice/item/index.html.twig', [
            'items' => $items,
            'products' => $productRepository->findAll(),
            'companies' => $companyRepository->findAll(),
        ]);
    }

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

