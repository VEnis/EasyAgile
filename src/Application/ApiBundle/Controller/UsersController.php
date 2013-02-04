<?php

namespace Application\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UsersController extends FOSRestController
{
    /**
     * Get list of all users
     *
     * @ApiDoc()
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getRepository("ApplicationSonataUserBundle:User")
            ->findAll();

        $view = $this
            ->view($users, "200")
            ->setSerializerGroups(array("users_simple"));
        return $this->handleView($view);
    }

    /**
     * Get single user by it's id
     *
     * @param $id Id of the user
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc
     */
    public function getUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ApplicationSonataUserBundle:User")
            ->find($id);

        if(!$user) {
            throw $this->createNotFoundException();
        }
        $view = $this
            ->view($user, "200")
            ->setSerializerGroups(array("sessions_simple", "users_full"));
        return $this->handleView($view);
    }
}
