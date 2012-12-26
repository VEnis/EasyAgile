<?php

namespace Application\PlanningPokerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Story
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Application\PlanningPokerBundle\Entity\StoryRepository")
 */
class Story
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="estimate", type="integer")
     */
    private $estimate;

    /**
     * @var array
     *
     * @ORM\Column(name="custom_fields", type="array")
     */
    private $custom_fields;

    /**
     * @ORM\ManyToOne(targetEntity="Session", inversedBy="stories")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
     */
    private $session;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Story
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set estimate
     *
     * @param integer $estimate
     * @return Story
     */
    public function setEstimate($estimate)
    {
        $this->estimate = $estimate;
    
        return $this;
    }

    /**
     * Get estimate
     *
     * @return integer 
     */
    public function getEstimate()
    {
        return $this->estimate;
    }

    /**
     * Set custom_fields
     *
     * @param array $customFields
     * @return Story
     */
    public function setCustomFields($customFields)
    {
        $this->custom_fields = $customFields;
    
        return $this;
    }

    /**
     * Get custom_fields
     *
     * @return array 
     */
    public function getCustomFields()
    {
        return $this->custom_fields;
    }

    /**
     * Set session
     *
     * @param \Application\PlanningPokerBundle\Entity\Session $session
     * @return Story
     */
    public function setSession(\Application\PlanningPokerBundle\Entity\Session $session = null)
    {
        $this->session = $session;
    
        return $this;
    }

    /**
     * Get session
     *
     * @return \Application\PlanningPokerBundle\Entity\Session 
     */
    public function getSession()
    {
        return $this->session;
    }
}