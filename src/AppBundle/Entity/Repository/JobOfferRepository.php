<?php

namespace AppBundle\Entity\Repository;

use Alex\Pagination\Adapter\DoctrineQueryBuilderAdapter;
use Alex\Pagination\Pager;
use AppBundle\Entity\JobOffer;
use Doctrine\ORM\EntityRepository;

class JobOfferRepository extends EntityRepository
{
    public function findMostRecentOffers($keywords = null, $page = 1, $perPage = 15)
    {
        $builder = $this
            ->createQueryBuilder('j')
            ->where('j.status = :status')
            ->andWhere('j.expiresAt >= :today')
            ->orderBy('j.expiresAt', 'DESC')
            ->setParameter('status', JobOffer::PUBLISHED)
            ->setParameter('today', date('Y-m-d'))
        ;

        if ($keywords) {
            $builder
                ->andWhere('j.title LIKE :keywords OR j.description LIKE :keywords')
                ->setParameter('keywords', '%'.$keywords.'%')
            ;
        }

        $pager = new Pager(new DoctrineQueryBuilderAdapter($builder));
        $pager->setPage($page);
        $pager->setPerPage($perPage);

        return $pager;
    }
}
