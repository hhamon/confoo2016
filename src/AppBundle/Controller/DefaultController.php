<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}", name="app_hello")
     */
    public function helloAction($name)
    {
        return $this->render('default/hello.html.twig', [
            'my_name' => $name,
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'admin' => 'hugo',
        ]);
    }
}
