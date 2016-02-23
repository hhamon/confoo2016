<?php

namespace AppBundle\JobOffer;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResumeFileUploader
{
    private $uploadDirectory;

    public function __construct($uploadDirectory)
    {
        self::assertWritableDirectory($uploadDirectory);

        $this->uploadDirectory = $uploadDirectory;
    }

    private static function assertWritableDirectory($uploadDirectory)
    {
        if (!is_writable($uploadDirectory)) {
            throw new \RuntimeException(sprintf('Directory %s is not writable or doesn\'t exist!', $uploadDirectory));
        }
    }

    public function uploadResume(UploadedFile $file, $uploadDirectory = null)
    {
        $directory = $this->uploadDirectory;
        if (null !== $uploadDirectory) {
            self::assertWritableDirectory($uploadDirectory);
            $directory = $uploadDirectory;
        }

        return $file->move($directory, $this->generateFilename($file));
    }

    private function generateFilename(UploadedFile $file)
    {
        return sprintf('%s.%s',
            md5(uniqid(mt_rand(0, 99999)).microtime()),
            $file->guessExtension()
        );
    }
}
