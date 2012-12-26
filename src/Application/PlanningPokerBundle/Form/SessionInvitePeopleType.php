<?php

namespace Application\PlanningPokerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SessionInvitePeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
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
        return 'application_planningpokerbundle_sessioninvitepeopletype';
    }
}
