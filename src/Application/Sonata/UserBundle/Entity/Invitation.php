<?php

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation
 *
 * @ORM\Table(name="invitation")
 * @ORM\Entity(repositoryClass="Application\Sonata\UserBundle\Entity\InvitationRepository")
 */
class Invitation
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", unique=true, length=6)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sent", type="boolean")
     */
    private $sent;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User", mappedBy="invitation", cascade={"persist", "merge"})
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->code = substr(md5(uniqid(rand(), true)), 0, 6);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Invitation
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set sent
     *
     * @param boolean $sent
     * @return Invitation
     */
    public function send()
    {
        $this->sent = true;
    
        return $this;
    }

    /**
     * Get sent
     *
     * @return boolean 
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Invitation
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}
