<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Edukodas\Bundle\TasksBundle\Entity\Course;
use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\TasksBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TasksController extends Controller
{
    public function editFormAction(int $taskId)
    {
        $task = $this->getDoctrine()->getRepository('EdukodasTasksBundle:Task')->find($taskId);

        if (!$task) {
            throw new NotFoundHttpException('Task not found');
        }

        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);

        return $this->render('EdukodasTemplateBundle:Task:form.html.twig', array(
            'form' => $form->createView())
        );
    }

    /**
     * @return JsonResponse
     */
    public function listAction()
    {
        $courses = $this->getUser()->getCourses();

        return new JsonResponse(array_map(function (Course $course) {
            return [
                'id' => $course->getId(),
                'tasks' => array_map(function (Task $task) {
                    return [
                        'id' => $task->getId(),
                        'name' => $task->getName(),
                    ];
                }, $course->getTasks()->toArray())
            ];
        }, $courses->toArray()));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task, ['user' => $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('edukodas_tasks_list');
        }

        return $this->render('EdukodasTasksBundle::addtask.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
