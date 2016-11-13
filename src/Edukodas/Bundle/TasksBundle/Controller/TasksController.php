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
        $em = $this
            ->getDoctrine()
            ->getManager();
        $tasks = $em
            ->getRepository('EdukodasTasksBundle:Task')
            ->findAll();

        return $this->render('EdukodasTasksBundle::tasks.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    public function addAction()
    {

    }

    public function removeAction($taskid)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $task = $em
            ->getRepository('EdukodasTasksBundle:Task')
            ->findOneById($taskid);
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('edukodas_tasks_list');
    }
}
