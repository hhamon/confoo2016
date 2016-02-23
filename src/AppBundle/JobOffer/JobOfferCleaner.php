<?php

namespace AppBundle\JobOffer;

use AppBundle\Entity\Repository\JobOfferRepository;
use Symfony\Component\Filesystem\Filesystem;

class JobOfferCleaner
{
    private $repository;
    private $filesystem;
    private $resumesDirectory;

    public function __construct(
        JobOfferRepository $repository,
        Filesystem $filesystem,
        $resumesDirectory
    )
    {
        $this->repository = $repository;
        $this->filesystem = $filesystem;
        $this->resumesDirectory = $resumesDirectory;
    }

    public function cleanup($company = null, $nbDays = 25)
    {
        $offers = $this->repository->findOutdatedOffers($company, $nbDays);
        
        $files = [];
        foreach ($offers as $offer) {
            $files = array_merge($files, $offer->getResumesFiles($this->resumesDirectory));
        }

        $this->repository->deleteOffers($offers);
        $this->filesystem->remove($files);

        return count($offers);
    }
}
