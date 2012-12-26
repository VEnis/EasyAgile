<?php

namespace Application\PlanningPokerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Application\PlanningPokerBundle\Validator\Constraint as Constraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Session
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Application\PlanningPokerBundle\Entity\SessionRepository")
 */
class Session
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed = false;

    /**
     * @ORM\OneToMany(targetEntity="Story", mappedBy="session")
     */
    private $stories;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="sessions")
     * @ORM\JoinTable(name="session_peoples")
     *
     * @Assert\All(constraints={
     *     @Constraint\UniqueInCollection(propertyPath ="username", message="Duplicates are not allowed")
     * })
     */
    private $peoples;


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
     * @return Session
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Session
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Session
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set is_completed
     *
     * @param $completed
     * @return Session
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    
        return $this;
    }

    /**
     * Get is_completed
     *
     * @return boolean 
     */
    public function getCompleted()
    {
        return $this->completed;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stories = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add story
     *
     * @param \Application\PlanningPokerBundle\Entity\Story $story
     * @return Session
     */
    public function addStory(\Application\PlanningPokerBundle\Entity\Story $story)
    {
        $this->stories[] = $story;
    
        return $this;
    }

    /**
     * Remove story
     *
     * @param \Application\PlanningPokerBundle\Entity\Story $story
     */
    public function removeStory(\Application\PlanningPokerBundle\Entity\Story $story)
    {
        $this->stories->removeElement($story);
    }

    /**
     * Get stories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStories()
    {
        return $this->stories;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->title;
    }

    /**
     * Add peoples
     *
     * @param \Application\Sonata\UserBundle\Entity\User $peoples
     * @return Session
     */
    public function addPeople(\Application\Sonata\UserBundle\Entity\User $peoples)
    {
        $this->peoples[] = $peoples;
    
        return $this;
    }

    /**
     * Remove peoples
     *
     * @param \Application\Sonata\UserBundle\Entity\User $peoples
     */
    public function removePeople(\Application\Sonata\UserBundle\Entity\User $peoples)
    {
        $this->peoples->removeElement($peoples);
    }

    /**
     * Get peoples
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPeoples()
    {
        return $this->peoples;
    }
}
