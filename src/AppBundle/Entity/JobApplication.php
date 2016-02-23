<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;

/**
 * @Table(name="job_applications")
 * @Entity
 */
class JobApplication
{
    /**
     * @Column(type="smallint")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\JobOffer", inversedBy="applications")
     * @JoinColumn(name="job_offer_id", onDelete="CASCADE")
     */
    private $jobOffer;

    /**
     * @Column(length=100)
     */
    private $candidateFullName;

    /**
     * @Column(length=100)
     */
    private $candidateEmailAddress;

    /**
     * @Column(type="text")
     */
    private $message;

    /**
     * @Column
     */
    private $resume;

    /**
     * @Column(type="datetime")
     */
    private $createdAt;

    public static function createApplicationFor(JobOffer $jobOffer, $fullName, $emailAddress, $message, $resume = null)
    {
        return new self($jobOffer, $fullName, $emailAddress, $message, $resume);
    }

    public function __construct(JobOffer $jobOffer, $fullName, $emailAddress, $message, $resume = null)
    {
        $this->jobOffer = $jobOffer;
        $this->jobOffer->addApplication($this);
        $this->candidateFullName = $fullName;
        $this->candidateEmailAddress = $emailAddress;
        $this->message = $message;
        $this->resume = $resume;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getJobOffer()
    {
        return $this->jobOffer;
    }

    public function getCandidateFullName()
    {
        return $this->candidateFullName;
    }

    public function getCandidateEmailAddress()
    {
        return $this->candidateEmailAddress;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getResume()
    {
        return $this->resume;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
