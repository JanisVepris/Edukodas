<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Edukodas\Bundle\TasksBundle\Entity\Course;
use Edukodas\Bundle\TasksBundle\Form\TaskListFilterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TaskListController extends Controller
{
    /**
     * @return Response
     */
    public function showListAction()
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->getFirstCourse();

        $courseForm = $this->createForm(TaskListFilterType::class);

        $courseForm->get('course')->setData($course);

        return $this->render('@EdukodasTemplate/Task/taskList.html.twig', [
            'filterForm' => $courseForm->createView(),
            'course' => $course,
        ]);
    }

    /**
     * @param Course $course
     * @return Response
     */
    public function getListAction(Course $course)
    {
        return $this->render('@EdukodasTemplate/Task/inc/_taskList.html.twig', [
            'course' => $course
        ]);
    }
}
