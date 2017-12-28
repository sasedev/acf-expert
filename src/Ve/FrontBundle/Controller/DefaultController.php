<?php

namespace Ve\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VeFrontBundle:Default:index.html.twig');
    }
}
