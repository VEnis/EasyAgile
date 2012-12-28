<?php

namespace Application\PlanningPokerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Application\PlanningPokerBundle\Entity\Session;
use Application\PlanningPokerBundle\Form\SessionType;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Application\PlanningPokerBundle\Form\SessionInvitePeopleType;
use Application\PlanningPokerBundle\Service\Jira;
use Application\PlanningPokerBundle\Entity\Story;

/**
 * Session controller.
 *
 * @Route("/session")
 * @PreAuthorize("isFullyAuthenticated()")
 */
class SessionController extends Controller
{
    /**
     * Lists all Session entities.
     *
     * @Route("/", name="poker_session")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'mySessions' => $this->getUser()->getMySessions(),
            'sessions' => $this->getUser()->getSessions()
        );
    }

    /**
     * Finds and displays a Session entity.
     *
     * @Route("/{id}/show", name="poker_session_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Displays a form to create a new Session entity.
     *
     * @Route("/new", name="poker_session_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Session();
        $form   = $this->createForm(new SessionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Session entity.
     *
     * @Route("/create", name="poker_session_create")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Session();
        $form = $this->createForm(new SessionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->addPeople($this->getUser());
            $entity->setOwnedBy($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Session entity.
     *
     * @Route("/{id}/edit", name="poker_session_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        $editForm = $this->createForm(new SessionType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing Session entity.
     *
     * @Route("/{id}/update", name="poker_session_update")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        $editForm = $this->createForm(new SessionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Session entity.
     *
     * @Route("/{id}/delete", name="poker_session_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('poker_session'));
    }

    /**
     * Invites peoples to session
     *
     * @Route("/{id}/invite", name="poker_session_invite")
     * @Template()
     */
    public function inviteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        $inviteForm = $this->createForm(new SessionInvitePeopleType(), $entity);

        return array(
            'entity'      => $entity,
            'invite_form'   => $inviteForm->createView()
        );
    }

    /**
     * Processes peoples invitation
     *
     * @Route("/{id}/invite-process", name="poker_session_invite_process")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:invite.html.twig")
     */
    public function inviteProcessAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        $inviteForm = $this->createForm(new SessionInvitePeopleType(), $entity);
        $inviteForm->bind($request);

        if ($inviteForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'invite_form'   => $inviteForm->createView()
        );
    }

    /**
     * Processes stories JIRA import
     *
     * @Route("/{id}/import/jira", name="poker_session_import_jira")
     * @Template()
     */
    public function importJiraAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        return array(
            'entity'      => $entity,
            'form'   => $this->createImportJiraForm()->createView()
        );
    }

    /**
     * Processes JIRA import
     *
     * @Route("/{id}/import/jira-process", name="poker_session_import_jira_process")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:importJira.html.twig")
     */
    public function importJiraProcessAction(Request $request, $id)
    {
        //project%20%3D%20APPLINK%20AND%20Release%20%3D%20"R6.0.0"
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Session entity.');
        }

        $form = $this->createImportJiraForm();
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $jql = $data["jql"];

            $jira = new Jira();
            $jira_stories = $jira->getTasksByJQL($data["jira_login"], $data["jira_password"], $data["jql"]);

            foreach($jira_stories as $jira_story)
            {
                $key = $jira_story["key"];
                $title = $jira_story["fields"]["summary"];

                $story = new Story();
                $story->setTitle(sprintf("[%s]: %s", $key, $title));
                $story->setEstimate(0);
                $story->setCustomFields(array("jira_key" => $key));
                $story->setSession($entity);

                $em->persist($story);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'invite_form'   => $form->createView()
        );
    }

    protected function createImportJiraForm()
    {
        $data = array(
            "jql" => 'project%20%3D%20APPLINK%20AND%20Release%20%3D%20"R6.0.0"'
        );
        return $this->createFormBuilder($data)
            ->add("jira_login", 'text')
            ->add('jira_password', 'password')
            ->add("jql", 'text')
            ->getForm()
        ;
    }
}
