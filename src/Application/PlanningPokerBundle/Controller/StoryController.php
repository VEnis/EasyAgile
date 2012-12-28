<?php

namespace Application\PlanningPokerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Application\PlanningPokerBundle\Entity\Story;
use Application\PlanningPokerBundle\Form\StoryType;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Application\PlanningPokerBundle\Entity\Session;
use Application\PlanningPokerBundle\Form\StorySetEstimateType;

/**
 * Story controller.
 *
 * @Route("/session/{session_id}/story")
 * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
 * @PreAuthorize("isFullyAuthenticated()")
 */
class StoryController extends Controller
{
    /**
     * Finds and displays a Story entity.
     *
     * @Route("/{id}/show", name="poker_session_story_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Story entity.
     *
     * @Route("/new", name="poker_session_story_new")
     * @Template()
     */
    public function newAction(Session $session)
    {
        $entity = new Story();
        $entity->setSession($session);
        $form   = $this->createForm(new StoryType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'session' => $session
        );
    }

    /**
     * Creates a new Story entity.
     *
     * @Route("/create", name="poker_session_story_create")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Story:new.html.twig")
     */
    public function createAction(Request $request, Session $session)
    {
        $entity  = new Story();
        $entity->setSession($session);
        $form = $this->createForm(new StoryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Story entity.
     *
     * @Route("/{id}/edit", name="poker_session_story_edit")
     * @Template()
     */
    public function editAction($id, Session $session)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $editForm = $this->createForm(new StoryType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'session' => $session
        );
    }

    /**
     * Edits an existing Story entity.
     *
     * @Route("/{id}/update", name="poker_session_story_update")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Story:edit.html.twig")
     */
    public function updateAction(Request $request, $id, Session $session)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $editForm = $this->createForm(new StoryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Story entity.
     *
     * @Route("/{id}/delete", name="poker_session_story_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id, Session $session)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
    }

    /**
     * Let's play the poker
     *
     * @param $id
     * @param \Application\PlanningPokerBundle\Entity\Session $session
     * @return array
     *
     * @Route("/{id}/play", name="poker_session_story_play")
     * @Method("GET")
     * @Template
     */
    public function playAction(Session $session, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $estimateForm = $this->createForm(new StorySetEstimateType(), $entity);

        return array(
            "story" => $entity,
            "session" => $session,
            "estimate_fofm" => $estimateForm->createView()
        );
    }

    /**
     * Game over
     *
     * @Route("/{id}/game-over", name="poker_session_story_gameover")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Story:play.html.twig")
     */
    public function gameoverAction(Request $request, $id, Session $session)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        $estimateForm = $this->createForm(new StorySetEstimateType(), $entity);
        $estimateForm->bind($request);

        if ($estimateForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $estimateForm->createView()
        );
    }
}
