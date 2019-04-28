<?php

namespace App\Controller\Backend\Product;

use App\Chart\Type\Bar\Bar;
use App\Chart\Type\Bar\BarChart;
use App\Controller\Backend\BaseController;
use App\Entity\Product\Product;
use App\Form\Product\ProductType;
use App\Repository\Product\ProductRepository;
use App\Util\Month;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/product/product")
 */
class ProductController extends BaseController {
    /**
     * @Route("/", name="backend_product_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, Request $request): Response {
        $products = $this->paginate($productRepository->findAllWithPaginator("name")->getQuery(), $request);
        return $this->render('backend/product/product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/new", name="backend_product_product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('backend_product_product_index');
        }

        return $this->render('backend/product/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="backend_product_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_product_product_index', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('backend/product/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="backend_product_product_show", methods={"GET"})
     */
    public function show(Request $request, Product $product, EntityManagerInterface $entityManager): Response {
        $year = date("Y");
        //Select expenses sum for a product by month on a given year
        $rawData = $entityManager->createQuery("
            SELECT SUM(item.quantity * item.pricePerUnit) as totalSpent, AVG(item.pricePerUnit) as averagePrice, SUM(item.quantity) as totalSold,
            MONTH(invoice.date) AS month, YEAR(invoice.date) AS year FROM App\Entity\Invoice\Invoice invoice
            JOIN invoice.items item JOIN item.product product WHERE product.id = :productId AND YEAR(invoice.date) = :year
            GROUP BY month, year ORDER BY year, month DESC
         ")
            ->setParameter("productId", $product->getId())
            ->setParameter("year", $year)
            ->execute();

        $chart = BarChart::createForYear(["Valor transacionado", "Quantidade vendida"]);
        $chart->setDecimalPlaces(2);

        foreach ($rawData as $elem) {
            $month = intval($elem["month"]);
            $bar = $chart->getBarByIndex("Valor transacionado", $month-1);
            $bar->setY(doubleval($elem["totalSpent"]));

            $bar = $chart->getBarByIndex("Quantidade vendida", $month-1);
            $bar->setY(doubleval($elem["totalSold"]));
        }

        return $this->render('backend/product/product/show.html.twig', [
            'product' => $product,
            'stats' => $chart,
        ]);
    }

    /**
     * @Route("/{id}", name="backend_product_product_delete", methods={"DELETE"})
     */
    public
    function delete(Request $request, Product $product): Response {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_product_product_index');
    }
}
