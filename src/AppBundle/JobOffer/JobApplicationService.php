<?php

namespace AppBundle\JobOffer;

use AppBundle\Entity\JobApplication;
use Doctrine\Common\Persistence\ObjectManager;

class JobApplicationService
{
    private $uploader;
    private $entityManager;

    public function __construct(ObjectManager $manager, ResumeFileUploader $uploader)
    {
        $this->entityManager = $manager;
        $this->uploader = $uploader;
    }

    public function createJobApplication(JobApplication $jobApplication)
    {
        if ($file = $jobApplication->getUploadedResume()) {
            $file = $this->uploader->uploadResume($file);
            $jobApplication->setResume($file->getBasename());
        }

        $this->entityManager->persist($jobApplication);
        $this->entityManager->flush();
    }

    
}
