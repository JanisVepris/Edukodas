<?php

namespace Edukodas\Bundle\TasksBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskListFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('course', EntityType::class, [
                'choice_label' => 'extendedName',
                'class' => 'Edukodas\Bundle\TasksBundle\Entity\Course',
            ]);
    }
}
