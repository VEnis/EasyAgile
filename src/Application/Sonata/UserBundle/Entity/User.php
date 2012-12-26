<?php

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Invitation
     *
     * @ORM\OneToOne(targetEntity="Invitation", inversedBy="user")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     * @Assert\NotNull(message="Your invitation is wrong")
     */
    protected $invitation;

    /**
     * @ORM\ManyToMany(targetEntity="Application\PlanningPokerBundle\Entity\Session", mappedBy="peoples")
     */
    protected $sessions;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set invitation
     *
     * @param Invitation $invitation
     * @return User
     */
    public function setInvitation(Invitation $invitation)
    {
        $this->invitation = $invitation;

        return $this;
    }

    /**
     * Get invitation
     *
     * @return Invitation
     */
    public function getInvitation()
    {
        return $this->invitation;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->sessions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add sessions
     *
     * @param \Application\PlanningPokerBundle\Entity\Session $sessions
     * @return User
     */
    public function addSession(\Application\PlanningPokerBundle\Entity\Session $sessions)
    {
        $this->sessions[] = $sessions;
    
        return $this;
    }

    /**
     * Remove sessions
     *
     * @param \Application\PlanningPokerBundle\Entity\Session $sessions
     */
    public function removeSession(\Application\PlanningPokerBundle\Entity\Session $sessions)
    {
        $this->sessions->removeElement($sessions);
    }

    /**
     * Get sessions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSessions()
    {
        return $this->sessions;
    }
}
