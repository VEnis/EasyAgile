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

        $this->createUser("ABevzyuk", "123123", "abevzyuk@luxoft.com", true, false);
        $this->createUser("SIdels", "123123", "sidels@luxoft.com", true, false);
        $this->createUser("YKoropatnyuk", "123123", "YKoropatnyuk@luxoft.com", true, false);
        $this->createUser("AMakarinskii", "123123", "AMakarinskii@luxoft.com", true, false);
        $this->createUser("SMironov", "123123", "SMironov@luxoft.com", true, false);
        $this->createUser("EProdan", "123123", "EProdan@luxoft.com", true, false);
        $this->createUser("ARusinov", "123123", "ARusinov@luxoft.com", true, false);
        $this->createUser("YSkriyka", "123123", "yskriyka@luxoft.com", true, false);
        $this->createUser("VSokolov", "123123", "VSokolov@luxoft.com", true, false);
        $this->createUser("MStankevich", "123123", "mstankevich@luxoft.com", true, false);
        $this->createUser("IZhivitsa", "123123", "IZhivitsa@luxoft.com", true, false);

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
