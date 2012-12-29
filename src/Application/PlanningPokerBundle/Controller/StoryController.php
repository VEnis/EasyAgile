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
        return array(
            'story'      => $this->getStory($id),
            'delete_form' => $this->createDeleteForm($id)->createView(),
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
        $story = new Story();
        $story->setSession($session);
        $form   = $this->createForm(new StoryType(), $story);

        return array(
            'story' => $story,
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
     */
    public function editAction($id, Session $session)
    {
        $story = $this->getStory($id);
        $editForm = $this->createForm(new StoryType(), $story);

        return array(
            'story'      => $story,
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
        $story = $this->getStory($id);

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
     */
    public function deleteAction(Request $request, $id, Session $session)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $this->getStory($id);

        $em->remove($story);
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
        $story = $this->getStory($id);
        $estimateForm = $this->createForm(new StorySetEstimateType(), $story);

        return array(
            "story" => $story,
            "session" => $session,
            "estimate_form" => $estimateForm->createView()
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
        $story = $this->getStory($id);
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
     */
    public function userEstimatesAction(Request $request, $id)
    {
        $story = $this->getStory($id);
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
     */
    public function setEstimateAction(Request $request, $id, $estimate, $user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $story = $this->getStory($id);
        $user = $em->getRepository("ApplicationSonataUserBundle:User")->find($user_id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User.');
        }

        //TODO: Implement estimate validation
        $story->setUsersEstimate($user, $estimate);

        $em->persist($story);
        $em->flush();

        return new Response(json_encode(array(
            "result" => true
        )));
    }

    /**
     * @param $id Story id
     * @return \Application\PlanningPokerBundle\Entity\Story
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getStory($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Story')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Story entity.');
        }

        return $entity;
    }
}
