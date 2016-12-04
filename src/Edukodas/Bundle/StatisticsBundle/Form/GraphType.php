<?php

namespace Edukodas\Bundle\StatisticsBundle\Form;

use Edukodas\Bundle\StatisticsBundle\Service\GraphService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class GraphType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('timespan', ChoiceType::class, [
                'choices' => [
                    'graph.from.year' => GraphService::FILTER_TIMESPAN_YEAR,
                    'graph.from.month' => GraphService::FILTER_TIMESPAN_MONTH,
                    'graph.from.week' => GraphService::FILTER_TIMESPAN_WEEK,
                    'graph.from.today' => GraphService::FILTER_TIMESPAN_TODAY,
                    'graph.from.all' => GraphService::FILTER_TIMESPAN_ALL
                ],
                'preferred_choices' => [
                    'graph.from.all' => GraphService::FILTER_TIMESPAN_ALL
                ],
                'label' => 'graph.from.label'
            ])
            ->add('class', EntityType::class, [
                'class' => 'Edukodas\Bundle\UserBundle\Entity\StudentClass',
                'choice_label' => 'title',
                'required' => false,
                'label' => 'graph.class'
            ])
            ->add('team', EntityType::class, [
                'class' => 'Edukodas\Bundle\UserBundle\Entity\StudentTeam',
                'choice_label' => 'title',
                'required' => false,
                'label' => 'graph.team'
            ]);
    }
}
