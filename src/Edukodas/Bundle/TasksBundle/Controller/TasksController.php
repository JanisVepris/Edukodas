<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Edukodas\Bundle\TasksBundle\Entity\Course;
use Edukodas\Bundle\TasksBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class TasksController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function listAction()
    {
        $courses = $this->getUser()->getCourses();

//        return new JsonResponse(array_map(function (Course $course) {
//            return [
//                'id' => $course->getId(),
//                'tasks' => array_map(function (Task $task) {
//                    return [
//                        'id' => $task->getId(),
//                        'name' => $task->getName(),
//                    ];
//                }, $course->getTasks()->toArray())
//            ];
//        }, $courses->toArray()));
    }

    public function addAction()
    {

    }

    public function newAction(Request $request)
    {
        // create a task and give it some dummy data for this example
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
