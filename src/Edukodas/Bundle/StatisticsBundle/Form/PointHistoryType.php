<?php

namespace Edukodas\Bundle\StatisticsBundle\Form;

use Edukodas\Bundle\TasksBundle\Entity\Task;
use Edukodas\Bundle\TasksBundle\Repository\TaskRepository;
use Edukodas\Bundle\UserBundle\Entity\User;
use Edukodas\Bundle\UserBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointHistoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teacher', EntityType::class, [
                'required' => false,
                'class' => User::class,
                'label' => 'form.add_points.teacher'
            ])
            ->add('task', EntityType::class, [
                'class' => Task::class,
                'query_builder' => function (TaskRepository $tr) use ($options) {
                    return $tr->createQueryBuilder('t')
                        ->join('t.course', 'c')
                        ->where('c.user = :user')
                        ->orderBy('t.name', 'ASC')
                        ->setParameter('user', $options['user']);
                },
                'group_by' => 'course',
                'label' => 'form.add_points.task',
            ])
            ->add('student', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->orderBy('u.lastName', 'ASC')
                        ->setParameter('role', '%"' . User::STUDENT_ROLE . '"%');
                },
                'label' => 'form.add_points.student'
            ])
            ->add('amount', NumberType::class, [
                'label' => 'form.add_points.amount'
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'form.add_points.comment'
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Edukodas\Bundle\StatisticsBundle\Entity\PointHistory',
            'user' => null,
            'course' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'edukodas_bundle_statisticsbundle_pointhistory';
    }
}
