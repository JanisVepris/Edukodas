<?php

namespace Edukodas\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EdukodasUserBundle:Default:index.html.twig');
    }
}
