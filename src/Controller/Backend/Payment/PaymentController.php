<?php

namespace App\Controller\Backend\Payment;

use App\Controller\Backend\BaseController;
use App\Entity\Payment\Payment;
use App\Form\Payment\PaymentType;
use App\Repository\Payment\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/payment/payment")
 */
class PaymentController extends BaseController
{
    /**
     * @Route("/", name="backend_payment_payment_index", methods={"GET"})
     */
    public function index(PaymentRepository $paymentRepository, Request $request): Response
    {
        $payments = $paymentRepository->findAllOrderedBy("date");
        $payments = $this->paginate($payments->getQuery(), $request);
        return $this->render('backend/payment/payment/index.html.twig', [
            'payments' => $payments,
        ]);
    }


    /**
     * @Route("/{id}", name="backend_payment_payment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Payment $payment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_payment_payment_index');
    }
}
