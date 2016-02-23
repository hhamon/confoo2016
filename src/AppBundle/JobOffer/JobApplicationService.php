<?php

namespace AppBundle\JobOffer;

use AppBundle\Entity\JobApplication;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JobApplicationService
{
    private $uploadDirectory;
    private $entityManager;

    public function __construct(ObjectManager $manager, $uploadDirectory)
    {
        if (!is_writable($uploadDirectory)) {
            throw new \RuntimeException(sprintf('Directory %s is not writable or doesn\'t exist!', $uploadDirectory));
        }

        $this->entityManager = $manager;
        $this->uploadDirectory = $uploadDirectory;
    }

    public function createJobApplication(JobApplication $jobApplication)
    {
        if ($file = $jobApplication->getUploadedResume()) {
            $name = $this->generateFilename($file);
            $file = $file->move($this->uploadDirectory, $name);
            $jobApplication->setResume($file->getBasename());
        }

        $this->entityManager->persist($jobApplication);
        $this->entityManager->flush();
    }

    private function generateFilename(UploadedFile $file)
    {
        return sprintf('%s.%s',
            md5(uniqid(mt_rand(0, 99999)).microtime()),
            $file->guessExtension()
        );
    }
}
