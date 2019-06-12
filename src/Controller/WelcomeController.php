<?php

namespace App\Controller;

use App\Doctrine\Filter\Filter;
use App\Doctrine\Filter\FilterCollection;
use App\Repository\Company\CompanyRepository;
use App\Repository\Invoice\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController {
    /**
     * @Route("/", name="welcome")
     */
    public function index() {
        return $this->redirectToRoute("backend_dashboard");
    }

    /**
     * @Route("/backend", name="welcome_backend")
     */
    public function backendIndex() {
        return $this->redirectToRoute("backend_dashboard");
    }


}
