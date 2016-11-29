<?php

namespace Edukodas\Bundle\UserBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, ['label' => 'form.username', 'translation_domain' => 'FOSUserBundle'])
            ->add(
                'email',
                LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'),
                [
                    'label' => 'form.email',
                    'translation_domain' => 'FOSUserBundle'
                ]
            );
    }
}
