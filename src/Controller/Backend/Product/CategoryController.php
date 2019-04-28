<?php

namespace App\Controller\Backend\Product;

use App\Controller\Backend\BaseController;
use App\Entity\Product\Category;
use App\Form\Product\CategoryType;
use App\Repository\Product\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/product/category")
 */
class CategoryController extends BaseController {
    /**
     * @Route("/", name="backend_product_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, Request $request): Response {
        $categories = $categoryRepository->findAllWithPaginator("name");
        $categories = $this->paginate($categories->getQuery(), $request);
        return $this->render('backend/product/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="backend_product_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('backend_product_category_index');
        }

        return $this->render('backend/product/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_product_category_show", methods={"GET"})
     */
    public function show(Category $category): Response {
        return $this->render('backend/product/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_product_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_product_category_index', [
                'id' => $category->getId(),
            ]);
        }

        return $this->render('backend/product/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_product_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response {
        if ($category->getProducts()->isEmpty() === false) {
            $this->addFlash("error", "Impossível apagar uma categoria com produtos associados.");
        } else if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_product_category_index');
    }
}
