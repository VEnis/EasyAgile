<?php

namespace Application\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SessionsController extends FOSRestController
{
    /**
     * Get single session by it's id
     *
     * @param $id Id of the session
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc
     */
    public function getSessionAction($id)
    {
        $user = $this->getDoctrine()->getRepository("ApplicationPlanningPokerBundle:Session")
            ->find($id);

        if(!$user) {
            throw $this->createNotFoundException();
        }
        $view = $this
            ->view($user, "200")
            ->setSerializerGroups(array("full_session"));
        return $this->handleView($view);
    }
}
