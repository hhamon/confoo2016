<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\JobOffer;
use Doctrine\ORM\EntityRepository;

class JobOfferRepository extends EntityRepository
{
    public function findMostRecentOffers()
    {
        $query = $this
            ->createQueryBuilder('j')
            ->where('j.status = :status')
            ->andWhere('j.expiresAt >= :today')
            ->orderBy('j.expiresAt', 'DESC')
            ->setParameter('status', JobOffer::PUBLISHED)
            ->setParameter('today', date('Y-m-d'))
            ->getQuery();

        return $query->getResult();
    }
}
