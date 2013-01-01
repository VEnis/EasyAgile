<?php

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\Controller\ChangePasswordController as BaseController;
use FOS\UserBundle\Model\UserInterface;

class ChangePasswordController extends BaseController
{
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('application_site_site_index');
    }
}
