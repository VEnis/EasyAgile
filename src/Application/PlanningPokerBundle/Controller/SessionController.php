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

/**
 * Session controller.
 *
 * @Route("/poker/session")
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
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ApplicationPlanningPokerBundle:Session')->findAll();

        return array(
            'entities' => $entities,
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

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new SessionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('poker_session_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Session entity.
     *
     * @Route("/{id}/delete", name="poker_session_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ApplicationPlanningPokerBundle:Session')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Session entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('poker_session'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
