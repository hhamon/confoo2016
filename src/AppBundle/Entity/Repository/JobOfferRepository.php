<?php

namespace AppBundle\Entity\Repository;

use Alex\Pagination\Adapter\DoctrineQueryBuilderAdapter;
use Alex\Pagination\Pager;
use AppBundle\Entity\JobOffer;
use Doctrine\ORM\EntityRepository;

class JobOfferRepository extends EntityRepository
{
    public function findActiveCompanies()
    {
        $records = $this
            ->createQueryBuilder('j')
            ->select('j.companyName')
            ->distinct()
            ->orderBy('j.companyName', 'ASC')
            ->getQuery()
            ->getArrayResult();

        $companies = [];
        foreach ($records as $record) {
            $companies[] = $record['companyName'];
        }
        
        return $companies;
    }

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
                ->andWhere('j.title LIKE :keywords OR j.description LIKE :keywords OR j.companyName LIKE :keywords')
                ->setParameter('keywords', '%'.$keywords.'%')
            ;
        }

        $pager = new Pager(new DoctrineQueryBuilderAdapter($builder));
        $pager->setPage($page);
        $pager->setPerPage($perPage);

        return $pager;
    }

    public function findOutdatedOffers($company = null, $nbDays = 25)
    {
        $expiration = date('Y-m-d 23:59:59', strtotime('-'.$nbDays.' days'));

        $qb = $this
            ->createQueryBuilder('j')
            ->select('j, a')
            ->leftJoin('j.applications', 'a')
            ->where('j.expiresAt < :expiration')
            ->setParameter('expiration', $expiration)
        ;

        if (null !== $company) {
            $qb
                ->andWhere('j.companyName = :company')
                ->setParameter('company', $company)
            ;
        }

        return $qb->getQuery()->getResult();
    }

    public function deleteOffers($offers)
    {
        foreach ($offers as $offer) {
            $this->_em->remove($offer);
        }

        $this->_em->flush();
    }
}
