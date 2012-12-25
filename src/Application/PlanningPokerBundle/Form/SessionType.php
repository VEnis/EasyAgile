<?php

namespace Application\PlanningPokerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('created_at')
            ->add('updated_at')
            ->add('completed')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlanningPokerBundle\Entity\Session'
        ));
    }

    public function getName()
    {
        return 'application_planningpokerbundle_sessiontype';
    }
}
