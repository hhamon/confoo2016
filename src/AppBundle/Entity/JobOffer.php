<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Table(name="job_offers", uniqueConstraints={
 *   @UniqueConstraint(
 *     name="job_offer_token_unique",
 *     columns="token"
 *   )
 * })
 * @Entity(repositoryClass="AppBundle\Entity\Repository\JobOfferRepository")
 */
class JobOffer
{
    const DRAFT = 'DRAFT';
    const PUBLISHED = 'PUBLISHED';

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
     * @Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @Column(type="datetime")
     */
    private $createdAt;

    public function __construct($title, $description, $companyName, $companyLogo = null, $token = null)
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
        $this->createdAt = new \DateTime();
        $this->expiresAt = new \DateTime('+30 days');
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
}
