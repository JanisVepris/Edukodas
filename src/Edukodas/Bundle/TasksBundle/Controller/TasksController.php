<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class TasksController extends Controller
{
    /**
     * @return Response
     */
    public function listAction()
    {
        $user = $this
            ->getUser()
            ->getId();

        $em = $this
            ->getDoctrine()
            ->getManager();

        $courses = $em
            ->getRepository('EdukodasTasksBundle:Course')
            ->findBy(['user' => $user]);

        $tasks = $em
            ->getRepository('EdukodasTasksBundle:Task')
            ->findBy(['course' => $courses]);
        $result = [];

        foreach ($tasks as $task) {
            $result[] = [
                'courseId' => $task->getCourse()->getId(),
                'courseName' => $task->getCourse()->getName(),
                'taskId' => $task->getId(),
                'taskName' => $task->getName(),
                'taskDescription' => $task->getDescription(),
                'taskPoints' => $task->getPoints(),
            ];
        }

        return new JsonResponse($result);
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
