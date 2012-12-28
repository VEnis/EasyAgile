<?php

namespace Application\PlanningPokerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StorySetEstimateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estimate', null, array(
                'label' => "Set result story estimate to"
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlanningPokerBundle\Entity\Story'
        ));
    }

    public function getName()
    {
        return 'application_planningpokerbundle_storytype';
    }
}
