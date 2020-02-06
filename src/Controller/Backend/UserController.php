<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\Model\ChangePassword;
use App\Form\User\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController {
    /**
     * @Route("/backend/user/change-password", name="backend_user_change_password")
     */
    public function index(Request $request) {
        $changePasswordModel = new ChangePassword();

        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $form->handleRequest($request);

        /**
         * @var $user User
         */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", 'A sua password foi alterada com sucesso.');
            $changePasswordModel->update($user);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_dashboard');
        }

        return $this->render('backend/user/change_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
