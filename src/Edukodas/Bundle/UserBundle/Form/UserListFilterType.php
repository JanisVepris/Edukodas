<?php

namespace Edukodas\Bundle\UserBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserListFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team', EntityType::class, [
                'class' => 'Edukodas\Bundle\UserBundle\Entity\StudentTeam',
                'choice_label' => 'title',
                'required' => false,
                'label' => 'user_list.team'
            ])
            ->add('class', EntityType::class, [
                'class' => 'Edukodas\Bundle\UserBundle\Entity\StudentClass',
                'choice_label' => 'title',
                'required' => false,
                'label' => 'user_list.class'
            ])
            ->setMethod('GET');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
