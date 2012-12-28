<?php

namespace Application\PlanningPokerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StoryEstimateByUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Application\PlanningPokerBundle\Entity\StoryEstimateByUserRepository")
 */
class StoryEstimateByUser
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
     * @var integer
     *
     * @ORM\Column(name="estimate", type="integer")
     */
    private $estimate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isEstimated", type="boolean")
     */
    private $isEstimated;

    /**
     * @ORM\ManyToOne(targetEntity="Application\PlanningPokerBundle\Entity\Story", inversedBy="usersEstimates")
     */
    protected $story;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="estimatedStories")
     */
    protected $user;


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
     * Set estimate
     *
     * @param integer $estimate
     * @return StoryEstimateByUser
     */
    public function setEstimate($estimate)
    {
        $this->estimate = $estimate;
        $this->isEstimated = true;
    
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
     * Set isEstimated
     *
     * @param boolean $isEstimated
     * @return StoryEstimateByUser
     */
    public function setIsEstimated($isEstimated)
    {
        $this->isEstimated = $isEstimated;
    
        return $this;
    }

    /**
     * Get isEstimated
     *
     * @return boolean 
     */
    public function getIsEstimated()
    {
        return $this->isEstimated;
    }

    /**
     * Set story
     *
     * @param \Application\PlanningPokerBundle\Entity\Story $story
     * @return StoryEstimateByUser
     */
    public function setStory(\Application\PlanningPokerBundle\Entity\Story $story = null)
    {
        $this->story = $story;
    
        return $this;
    }

    /**
     * Get story
     *
     * @return \Application\PlanningPokerBundle\Entity\Story 
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return StoryEstimateByUser
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}