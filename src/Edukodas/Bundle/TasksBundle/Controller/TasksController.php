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
     * @return Response
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

            return $this->render('EdukodasTemplateBundle:tasks:listtasks.html.twig', [
                'user' => $user,
            ]);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = $this->renderView('EdukodasTemplateBundle:tasks:addtask.html.twig', [
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_BAD_REQUEST);
        }

        return $this->render('EdukodasTemplateBundle:tasks:addtask.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $taskId
     * @return Response
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

            return $this->render('EdukodasTemplateBundle:tasks:listtasks.html.twig', [
                'user' => $user,
            ]);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = $this->renderView('EdukodasTemplateBundle:tasks:edittask.html.twig', [
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_BAD_REQUEST);
        }

        return $this->render('EdukodasTemplateBundle:tasks:edittask.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param int $taskId
     * @return Response
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

        return $this->render('EdukodasTemplateBundle:tasks:listtasks.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        $user = $this->getUser();

        return $this->render('EdukodasTemplateBundle:tasks:listtasks.html.twig', [
            'user' => $user,
        ]);
    }
}
