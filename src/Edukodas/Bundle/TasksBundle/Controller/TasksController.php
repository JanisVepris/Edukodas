<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    /**
     * @return Response
     */
    public function listAction()
    {
//        $em = $this
//            ->getDoctrine()
//            ->getManager();
//        $tasks = $em
//            ->getRepository('EdukodasTasksBundle:Task')
//            ->findAll();
//
//        return $this->render('EdukodasTasksBundle::tasks.html.twig', [
//            'tasks' => $tasks,
//        ]);
        return $this->render('EdukodasTasksBundle::tasks.html.twig', []);
    }
}
