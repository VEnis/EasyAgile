<?php

namespace Application\PlanningPokerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\SecurityExtraBundle\Annotation\SecureParam;
use Application\PlanningPokerBundle\Entity\Session;
use Application\PlanningPokerBundle\Form\SessionType;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Application\PlanningPokerBundle\Form\SessionInvitePeopleType;
use Application\PlanningPokerBundle\Service\Jira;
use Application\PlanningPokerBundle\Entity\Story;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

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
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="VIEW")
     */
    public function showAction(Session $session)
    {
        return array(
            'entity' => $session
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

        return array(
            'entity' => $entity,
            'form'   => $this->createForm(new SessionType(), $entity)->createView(),
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

            // Creating ACL
            $aclProvider = $this->get("security.acl.provider");
            $objectIdentity = ObjectIdentity::fromDomainObject($entity);
            $acl = $aclProvider->createAcl($objectIdentity);

            // Retrieving security identity of current user
            $securityContext = $this->get("security.context");
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            // Granting owner access
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

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
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @Template()
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function editAction(Session $session)
    {
        return array(
            'entity'      => $session,
            'edit_form'   => $this->createForm(new SessionType(), $session)->createView()
        );
    }

    /**
     * Edits an existing Session entity.
     *
     * @Route("/{id}/update", name="poker_session_update")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:edit.html.twig")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function updateAction(Request $request, Session $session)
    {
        $editForm = $this->createForm(new SessionType(), $session);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity'      => $session,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Session entity.
     *
     * @Route("/{id}/delete", name="poker_session_delete")
     * @Method("GET")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="DELETE")
     */
    public function deleteAction(Session $session)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($session);
        $em->flush();

        return $this->redirect($this->generateUrl('poker_session'));
    }

    /**
     * Invites peoples to session
     *
     * @Route("/{id}/invite", name="poker_session_invite")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="EDIT")
     * @Template()
     */
    public function inviteAction(Session $session)
    {
        return array(
            'entity'        => $session,
            'invite_form'   => $this->createForm(new SessionInvitePeopleType(), $session)->createView()
        );
    }

    /**
     * Processes peoples invitation
     *
     * @Route("/{id}/invite-process", name="poker_session_invite_process")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:invite.html.twig")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function inviteProcessAction(Request $request, Session $session)
    {
        $inviteForm = $this->createForm(new SessionInvitePeopleType(), $session);
        $inviteForm->bind($request);

        if ($inviteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity'        => $session,
            'invite_form'   => $inviteForm->createView()
        );
    }

    /**
     * Processes stories JIRA import
     *
     * @Route("/{id}/import/jira", name="poker_session_import_jira")
     * @Template()
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function importJiraAction(Session $session)
    {
        return array(
            'entity'    => $session,
            'form'      => $this->createImportJiraForm()->createView()
        );
    }

    /**
     * Processes JIRA import
     *
     * @Route("/{id}/import/jira-process", name="poker_session_import_jira_process")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:importJira.html.twig")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function importJiraProcessAction(Request $request, Session $session)
    {
        //project%20%3D%20APPLINK%20AND%20Release%20%3D%20"R6.0.0"
        $form = $this->createImportJiraForm();
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

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
                $story->setSession($session);

                $em->persist($story);
            }

            $em->persist($session);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity'      => $session,
            'form'   => $form->createView()
        );
    }

    /**
     * Processes stories JIRA export
     *
     * @Route("/{id}/export/jira-all", name="poker_session_export_jira_all")
     * @Template()
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function exportAllJiraAction(Session $session)
    {
        return array(
            'entity' => $session,
            'form'   => $this->createExportJiraAllForm()->createView()
        );
    }

    /**
     * Processes JIRA export
     *
     * @Route("/{id}/export/jira-all-process", name="poker_session_export_jira_all_process")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:exportAllJira.html.twig")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function exportAllJiraProcessAction(Request $request, Session $session)
    {
        $form = $this->createExportJiraAllForm();
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $jira = new Jira();
            $jira->updateTasksEstimates($data["jira_login"], $data["jira_password"], $session->getStories());
            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity'      => $session,
            'form'   => $form->createView()
        );
    }

    /**
     * Processes stories JIRA export
     *
     * @Route("/{id}/export/jira/{story_id}", name="poker_session_export_jira")
     * @Template()
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "story_id"})
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function exportJiraAction(Session $session, Story $story)
    {
        return array(
            'entity'    => $session,
            'story'     => $story,
            'form'      => $this->createExportJiraAllForm()->createView()
        );
    }

    /**
     * Processes JIRA export
     *
     * @Route("/{id}/export/jira-process/{story_id}", name="poker_session_export_jira_process")
     * @Method("POST")
     * @Template("ApplicationPlanningPokerBundle:Session:exportJira.html.twig")
     * @ParamConverter("session", class="ApplicationPlanningPokerBundle:Session")
     * @ParamConverter("story", class="ApplicationPlanningPokerBundle:Story", options={"id" = "story_id"})
     * @SecureParam(name="session", permissions="EDIT")
     */
    public function exportJiraProcessAction(Request $request, Session $session, Story $story)
    {
        $form = $this->createExportJiraAllForm();
        $form->bind($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $jira = new Jira();
            $jira->updateTasksEstimates($data["jira_login"], $data["jira_password"], array($story));
            return $this->redirect($this->generateUrl('poker_session_show', array('id' => $session->getId())));
        }

        return array(
            'entity'      => $session,
            'story'      => $story,
            'form'   => $form->createView()
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

    protected function createExportJiraAllForm()
    {
        return $this->createFormBuilder()
            ->add("jira_login", 'text')
            ->add('jira_password', 'password')
            ->getForm()
            ;
    }
}
