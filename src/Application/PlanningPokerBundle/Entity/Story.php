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
     * @var float
     *
     * @ORM\Column(name="estimate", type="float")
     */
    private $estimate = 0;

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
     * @ORM\OneToMany(targetEntity="StoryEstimateByUser", mappedBy="story",cascade={"persist"})
     */
    protected $usersEstimates;


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
     * @param float $estimate
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
     * @return float
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

    /**
     * @return string
     */
    function __toString()
    {
        return $this->title;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usersEstimates = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add usersEstimates
     *
     * @param \Application\PlanningPokerBundle\Entity\StoryEstimateByUser $usersEstimates
     * @return Story
     */
    public function addUsersEstimate(\Application\PlanningPokerBundle\Entity\StoryEstimateByUser $usersEstimates)
    {
        $this->usersEstimates[] = $usersEstimates;
    
        return $this;
    }

    /**
     * Remove usersEstimates
     *
     * @param \Application\PlanningPokerBundle\Entity\StoryEstimateByUser $usersEstimates
     */
    public function removeUsersEstimate(\Application\PlanningPokerBundle\Entity\StoryEstimateByUser $usersEstimates)
    {
        $this->usersEstimates->removeElement($usersEstimates);
    }

    /**
     * Get usersEstimates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsersEstimates()
    {
        return $this->usersEstimates;
    }

    public function setUsersEstimate(\Application\Sonata\UserBundle\Entity\User $user, $estimate)
    {
        $existingEstimation = null;

        foreach ($this->getUsersEstimates() as $userEstimate)
        {
            if($userEstimate->getUser() == $user)
            {
                $existingEstimation = $userEstimate;
                break;
            }
        }

        if(is_null($existingEstimation))
        {
            $s = new StoryEstimateByUser();
            $s->setEstimate($estimate);
            $s->setStory($this);
            $s->setUser($user);

            $this->addUsersEstimate($s);
        }
        else
        {
            $existingEstimation->setEstimate($estimate);
        }
    }
}