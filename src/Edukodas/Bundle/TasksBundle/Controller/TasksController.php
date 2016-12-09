<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\TasksBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $user = $this->getUser();

        $task = new Task();

        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->render('@EdukodasTemplate/Profile/inc/_listTasks.html.twig', [
                'user' => $user,
            ]);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = $this->renderView('@EdukodasTemplate/Profile/inc/_addTaskForm.html.twig', [
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_BAD_REQUEST);
        }

        return $this->render('@EdukodasTemplate/Profile/inc/_addTaskForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function editFormAction(Request $request, Task $task)
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER', 'edit');

        $user = $this->getUser();

        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->render('@EdukodasTemplate/Profile/inc/_listTasks.html.twig', [
                'user' => $user,
            ]);
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $view = $this->renderView('@EdukodasTemplate/Profile/inc/_editTaskForm.html.twig', [
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_BAD_REQUEST);
        }

        return $this->render('@EdukodasTemplate/Profile/inc/_editTaskForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Task $task
     * @return Response
     */
    public function deleteAction(Task $task)
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('ROLE_TEACHER', 'delete');

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($task);
        $em->flush();

        return $this->render('@EdukodasTemplate/Profile/inc/_listTasks.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        $user = $this->getUser();

        return $this->render('@EdukodasTemplate/Profile/inc/_listTasks.html.twig', [
            'user' => $user,
        ]);
    }
}
