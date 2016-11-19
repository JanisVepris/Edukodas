<?php

namespace Edukodas\Bundle\TasksBundle\Controller;

use Edukodas\Bundle\TasksBundle\Entity\Course;
use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\TasksBundle\Repository\CourseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TasksController extends Controller
{
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

    public function addAction(Request $request)
    {
        $task = new Task();

        $form = $this->createFormBuilder($task)
            ->add('course', EntityType::class, [
                'class' => 'EdukodasTasksBundle:Course',
                'query_builder' => function (CourseRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->orderBy('c.name', 'ASC')
                        ->setParameter('user', $this->getUser());
                },
            ])
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('points', NumberType::class)
            ->add('save', SubmitType::class, array('label' => 'Create task'))
            ->getForm();

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
