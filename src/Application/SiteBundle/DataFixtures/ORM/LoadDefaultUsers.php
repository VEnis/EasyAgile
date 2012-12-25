<?php

namespace Application\SiteBundle\DataFixtures\ORM;

use FOS\UserBundle\Util\UserManipulator;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Application\Sonata\UserBundle\Entity\Invitation;
use Application\Sonata\UserBundle\Entity\User;

class LoadDefaultUsers implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->createUser("VEnis", "123123", "venis@luxoft.com", true, true);

        $this->manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Creates user and invitation for it
     * @param $username
     * @param $password
     * @param $email
     * @param $active
     * @param $superadmin
     */
    protected function createUser($username, $password, $email, $active, $superadmin)
    {
        // Creating invitation
        $invitation = new Invitation();
        $invitation->setEmail($email);
        $invitation->send();
        $this->manager->persist($invitation);

        // Creating user
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((Boolean) $active);
        $user->setSuperAdmin((Boolean) $superadmin);
        $user->setInvitation($invitation);

        $userManager->updateUser($user, false);
    }

}
