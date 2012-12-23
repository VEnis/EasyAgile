<?php

namespace Application\SiteBundle\DataFixtures\ORM;

use FOS\UserBundle\Util\UserManipulator;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadDefaultUsers implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $manipulator = $this->container->get('fos_user.util.user_manipulator');
        $manipulator->create("VEnis", "MyDatacom_Passwd!", "venis@difane.com", true, true);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
