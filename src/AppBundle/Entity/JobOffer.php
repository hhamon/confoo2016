<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Table(
 *   name="job_offers",
 *   uniqueConstraints={
 *     @UniqueConstraint(
 *       name="job_offer_token_unique",
 *       columns="token"
 *     )
 *   },
 *   indexes={
 *     @Index(
 *       name="job_offer_search_index",
 *       columns={ "title", "status", "expires_at", "company_name" }
 *     ),
 *     @Index(
 *       name="job_offer_companies_index",
 *       columns="company_name"
 *     )
 *   }
 * )
 * @Entity(repositoryClass="AppBundle\Entity\Repository\JobOfferRepository")
 */
class JobOffer
{
    const DRAFT = 'DRAFT';
    const PUBLISHED = 'PUBLISHED';

    const FULL_TIME = 'full-time';
    const PART_TIME = 'part-time';
    const FREELANCE = 'freelance';
    const TEMPORARY = 'temporary';

    /**
     * @Column(type="smallint")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(length=32)
     */
    private $token;

    /**
     * @Column(length=100)
     */
    private $title;

    /**
     * @Column(type="text")
     */
    private $description;

    /**
     * @Column(length=30)
     */
    private $companyName;

    /**
     * @Column(nullable=true)
     */
    private $companyLogo;

    /**
     * @Column(length=15)
     */
    private $status;

    /**
     * @Column(length=30)
     */
    private $position;

    /**
     * @Column(length=50)
     */
    private $city;

    /**
     * @Column(length=50, nullable=true)
     */
    private $state;

    /**
     * @Column(length=50)
     */
    private $country;

    /**
     * @Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @Column(type="datetime")
     */
    private $createdAt;

    /**
     * @OneToMany(targetEntity="AppBundle\Entity\JobApplication", mappedBy="jobOffer")
     */
    private $applications;

    public function __construct($title, $description, $companyName, $city, $country, $state = null, $position = self::FULL_TIME, $companyLogo = null, $token = null)
    {
        if (null === $token) {
            $token = md5(uniqid(mt_rand(0, 999999)).microtime());
        }

        $this->title = $title;
        $this->description = $description;
        $this->companyName = $companyName;
        $this->companyLogo = $companyLogo;
        $this->token = $token;
        $this->status = self::DRAFT;
        $this->position = $position;
        $this->city = $city;
        $this->country = $country;
        $this->state = $state;
        $this->createdAt = new \DateTime();
        $this->expiresAt = new \DateTime('+30 days');
        $this->applications = new ArrayCollection();
    }

    public function getApplications()
    {
        return $this->applications;
    }

    public function addApplication(JobApplication $application)
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
        }
    }

    public function publish($days = 30)
    {
        $this->status = self::PUBLISHED;
        $this->expiresAt = new \DateTime('+'. $days .' days');
    }

    private function isPublished()
    {
        return self::PUBLISHED === $this->status;
    }

    public function isActive()
    {
        return $this->isPublished() && !$this->isExpired();
    }

    public function isExpired()
    {
        $maxExpirationTime = strtotime('Y-m-d 23:59:59');
        $offerExpirationTime = $this->expiresAt->format('U');

        return $offerExpirationTime < $maxExpirationTime;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function getCompanyLogo()
    {
        return $this->companyLogo;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getCountry()
    {
        return $this->country;
    }
}
