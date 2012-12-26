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
            ->add('peoples', 'collection', array(
                'type' => 'genemu_jqueryselect2_entity',
                'options' => array(
                    'class' => 'ApplicationSonataUserBundle:User',
                    'property' => 'username'
                ),
                'allow_add' => true,
                'allow_delete' => true,
                'label' => null,
                'by_reference' => false
            ))
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
