<?php

namespace Edukodas\Bundle\TasksBundle\Form;

use Edukodas\Bundle\TasksBundle\Repository\CourseRepository;
use Edukodas\Bundle\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('course', EntityType::class, [
                'class' => 'EdukodasTasksBundle:Course',
                'query_builder' => function (CourseRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->orderBy('c.name', 'ASC')
                        ->setParameter('user', $options['user']);
                },
            ])
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('points', NumberType::class)
            ->add('save', SubmitType::class, array('label' => 'Create task'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Edukodas\Bundle\TasksBundle\Entity\Task',
            'user' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'edukodas_bundle_tasksbundle_task';
    }


}
