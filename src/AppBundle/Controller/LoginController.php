<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    /**
     * @Route("admin/login",name="admin_login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // get the login error if there is one
            $error = $authUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authUtils->getLastUsername();

            $form = $this->get('form.factory')->createNamed(null, LoginForm::class );

            return $this->render('AppBundle:login:login.html.twig', array(
                'last_username' => $lastUsername,
                'form'          => $form->createView(),
                'error'         => $error,
            ));
        }
        else return $this->redirect($this->generateUrl('admin_home'));
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logoutAction()
    {

    }
}
