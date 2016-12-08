<?php

namespace Edukodas\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RemoveProfilePictureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('remove', SubmitType::class, [
                'label' => 'profile.edit.remove',
                'attr' => [ 'class' => 'waves-effect waves-light btn red darken-3']
            ]);
    }
}
