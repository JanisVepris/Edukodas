<?php

namespace Edukodas\Bundle\FixturesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EdukodasFixturesBundle:Default:index.html.twig');
    }
}
