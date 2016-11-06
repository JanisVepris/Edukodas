<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EdukodasTasksBundle:Default:index.html.twig');
    }
}
