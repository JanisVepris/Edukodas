<?php

namespace Edukodas\Bundle\TasksBundle\Form;

use Edukodas\Bundle\TasksBundle\Repository\CourseRepository;
use Leafo\ScssPhp\Node\Number;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'query_builder' => function (CourseRepository $cr) use ($options) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->orderBy('c.name', 'ASC')
                        ->setParameter('user', $options['user']);
                },
                'label' => 'form.add_tasks.course',
            ])
            ->add('name', TextType::class, ['label' => 'form.add_tasks.name'])
            ->add('description', TextType::class, ['label' => 'form.add_tasks.description'])
            ->add('points', NumberType::class, ['label' => 'form.add_tasks.points']);
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
