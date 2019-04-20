<?php

namespace App\Controller\Backend\Payment;

use App\Entity\Payment\Payment;
use App\Form\Payment\PaymentType;
use App\Repository\Payment\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment/payment")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/", name="backend_payment_payment_index", methods={"GET"})
     */
    public function index(PaymentRepository $paymentRepository): Response
    {
        return $this->render('backend/payment/payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
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
