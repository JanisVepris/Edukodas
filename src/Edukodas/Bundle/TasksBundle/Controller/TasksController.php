<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\TasksBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TasksController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function addAction(Request $request)
    {
        $user = $this->getUser();

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->render('@EdukodasTasks/listtasks.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('@EdukodasTasks/addtask.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $taskId
     * @return JsonResponse|Response
     */
    public function editFormAction(Request $request, int $taskId)
    {
        $user = $this->getUser();

        $task = $this->getDoctrine()->getRepository('EdukodasTasksBundle:Task')->find($taskId);

        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }

        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->render('@EdukodasTasks/listtasks.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('@EdukodasTasks/edittask.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $taskId
     * @return JsonResponse
     */
    public function deleteAction(int $taskId)
    {
        $user = $this->getUser();

        $task = $this->getDoctrine()->getRepository('EdukodasTasksBundle:Task')->find($taskId);

        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($task);
        $em->flush();

        return $this->render('@EdukodasTasks/listtasks.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        $user = $this->getUser();

        return $this->render('@EdukodasTasks/listtasks.html.twig', [
            'user' => $user,
        ]);
    }
}
