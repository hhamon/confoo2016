<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\JobOffer;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadJobOffersData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $job1 = new JobOffer(
            'Symfony Developer',
            'This position is for a PHP/Symfony developer.',
            'SensioLabs'
        );

        $job2 = new JobOffer(
            'Drupal 8 Developer',
            'This position is for a PHP/Drupal 8 developer.',
            'Acquia'
        );

        $job3 = new JobOffer(
            'Joomla Developer',
            'This position is for a PHP/Joomla developer.',
            'Acquia'
        );
        $job3->publish(45);

        $manager->persist($job1);
        $manager->persist($job2);
        $manager->persist($job3);
        $manager->flush();
    }
}
