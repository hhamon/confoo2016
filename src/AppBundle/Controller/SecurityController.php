<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="app_login")
     * @Method("GET")
     */
    public function loginAction()
    {
        $utils = $this->get('security.authentication_utils');

        return $this->render('login.html.twig', [
            'username' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/login", name="app_login_check")
     * @Method("POST")
     */
    public function loginCheckAction()
    {
        // this code is never executed!!!
    }

    /**
     * @Route("/logout", name="app_logout")
     * @Method("GET")
     */
    public function logoutAction()
    {
        // this code is never executed!!!
    }
}
