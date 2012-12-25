<?php

namespace Application\SiteBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Application\Sonata\UserBundle\Entity\Invitation;

class LoadDefaultInvitations implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $invitationsCount = 20;
        foreach(range(1, $invitationsCount) as $i)
        {
            $i = new Invitation();
            $i->setEmail("");
            $manager->persist($i);
        }

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
