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
use Symfony\Component\HttpFoundation\Response;

/**
 * Story controller.
 *
 * @Route("/session/{session_id}/story")
 * @PreAuthorize("isFullyAuthenticated()")
 */
class StoryController extends Controller
{
    /**
     * Displays a form to create a new Story entity.
     *
     * @Route("/new", name="poker_session_story_new")
     * @Template()
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
     */
    public function newAction(Session $session)
    {
        $story = new Story();
        $story->setSession($session);

        return array(
            'story' => $story,
            'form'   => $this->createForm(new StoryType(), $story)->createView(),
            'session' => $session
        );
    }

    /**
     * Creates a new Story entity.
     *
     * @Route("/create", name="poker_session_story_create")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Story:new.html.twig")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
     */
    public function createAction(Request $request, Session $session)
    {
        $story  = new Story();
        $story->setSession($session);
        $form = $this->createForm(new StoryType(), $story);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'story' => $story,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Story entity.
     *
     * @Route("/{id}/edit", name="poker_session_story_edit")
     * @Template()
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "id"})
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
     */
    public function editAction(Story $story, Session $session)
    {
        return array(
            'story'     => $story,
            'edit_form' => $this->createForm(new StoryType(), $story)->createView(),
            'session'   => $session
        );
    }

    /**
     * Edits an existing Story entity.
     *
     * @Route("/{id}/update", name="poker_session_story_update")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Story:edit.html.twig")
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "id"})
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
     */
    public function updateAction(Request $request, Story $story, Session $session)
    {
        $editForm = $this->createForm(new StoryType(), $story);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'story'      => $story,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Story entity.
     *
     * @Route("/{id}/delete", name="poker_session_story_delete")
     * @Method("GET")
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "id"})
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
     */
    public function deleteAction(Story $story, Session $session)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($story);
        $em->flush();

        return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
    }

    /**
     * Let's play the poker
     *
     * @Route("/{id}/play", name="poker_session_story_play")
     * @Method("GET")
     * @Template
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "id"})
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
     */
    public function playAction(Session $session, Story $story)
    {
        return array(
            "story" => $story,
            "session" => $session,
            "estimate_form" => $this->createForm(new StorySetEstimateType(), $story)->createView()
        );
    }

    /**
     * Game over
     *
     * @Route("/{id}/game-over", name="poker_session_story_gameover")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Story:play.html.twig")
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "id"})
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session", options={"id" = "session_id"})
     */
    public function gameoverAction(Request $request, Story $story, Session $session)
    {
        $estimateForm = $this->createForm(new StorySetEstimateType(), $story);
        $estimateForm->bind($request);

        if ($estimateForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'story'      => $story,
            'edit_form'   => $estimateForm->createView()
        );
    }

    /**
     *
     * @Route("/{id}/estimates", name="poker_session_story_estimates")
     * @Method("GET")
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "id"})
     */
    public function userEstimatesAction(Story $story)
    {
        $estimates = $story->getUsersEstimates();

        $expectedEstimatesCount = $story->getSession()->getPeoples()->count();
        $actualEstimatesCount = $estimates->count();

        $result = array(
            "complete" => $expectedEstimatesCount == $actualEstimatesCount,
            "estimates" => array()
        );
        foreach($estimates as $estimate)
        {
            if($estimate->getIsEstimated())
            {
                $result["estimates"][] = array(
                    "uid" => $estimate->getUser()->getId(),
                    "estimate" => $estimate->getEstimate()
                );
            }
        }

        return new Response(json_encode($result));
    }

    /**
     * Game over
     *
     * @Route("/{id}/set-estimate/{user_id}/{estimate}", name="poker_session_story_set_estimate")
     * @Template("ApplicationPlanningPokerBundle:Story:play.html.twig")
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "id"})
     * @ParamConverter("user", class="ApplicationSonataUserBundle:User", options={"id" = "user_id"})
     */
    public function setEstimateAction(Story $story, $estimate, $user)
    {
        $em = $this->getDoctrine()->getManager();

        //TODO: Implement estimate validation
        $story->setUsersEstimate($user, $estimate);

        $em->persist($story);
        $em->flush();

        return new Response(json_encode(array(
            "result" => true
        )));
    }
}
