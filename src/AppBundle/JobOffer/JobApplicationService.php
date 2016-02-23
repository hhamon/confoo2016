<?php

namespace AppBundle\JobOffer;

use AppBundle\Entity\JobApplication;
use Doctrine\Common\Persistence\ObjectManager;

class JobApplicationService
{
    private $uploader;
    private $entityManager;
    private $mailer;

    public function __construct(ObjectManager $manager, ResumeFileUploader $uploader, \Swift_Mailer $mailer)
    {
        $this->entityManager = $manager;
        $this->uploader = $uploader;
        $this->mailer = $mailer;
    }

    public function createJobApplication(JobApplication $jobApplication)
    {
        if ($file = $jobApplication->getUploadedResume()) {
            $file = $this->uploader->uploadResume($file);
            $jobApplication->setResume($file->getBasename());
        }

        $this->entityManager->persist($jobApplication);
        $this->entityManager->flush();

        $this->notifyCompany($jobApplication, $file->getRealPath());
    }

    private function notifyCompany(JobApplication $jobApplication, $resumeFilePath)
    {
        $attachment = \Swift_Attachment::fromPath($resumeFilePath);
        $attachment->setFilename('resume.pdf');

        $message = \Swift_Message::newInstance()
            ->setFrom($jobApplication->getCandidateEmailAddress(), $jobApplication->getCandidateFullName())
            ->setTo($jobApplication->getCompanyContactEmailAddress())
            ->setSubject('New job application')
            ->setBody($jobApplication->getMessage())
            ->attach($attachment)
        ;

        $this->mailer->send($message);
    }
}
