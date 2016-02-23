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
            'hello@sensiolabs.com',
            'SensioLabs',
            'London',
            'United Kingdom'
        );

        $job2 = new JobOffer(
            'Drupal 8 Developer',
            'This position is for a PHP/Drupal 8 developer.',
            'contact@acquia.com',
            'Acquia',
            'Los Angeles',
            'United States',
            'CA',
            JobOffer::FREELANCE
        );

        $job3 = new JobOffer(
            'Joomla Developer',
            'This position is for a PHP/Joomla developer.',
            'contact@acquia.com',
            'Acquia',
            'San Francisco',
            'United States',
            'CA',
            JobOffer::PART_TIME
        );
        $job3->publish(45);

        for ($i = 1; $i <= 150; $i++) {
            $location = $this->getRandomLocation();
            $jobX = new JobOffer(
                $this->getRandomJobTitle(),
                'This position is for a developer.',
                $this->getRandomEmailAddress(),
                $this->getRandomCompany(),
                $location['city'],
                $location['country'],
                $location['state'],
                $this->getRandomPosition()
            );

            if (rand(0, 1)) {
                $jobX->publish(rand(30, 50));
            }

            $manager->persist($jobX);
        }
        
        $manager->persist($job1);
        $manager->persist($job2);
        $manager->persist($job3);
        $manager->flush();
    }

    private function getRandomCompany()
    {
        $companies = ['FooLab', 'Microsoft', 'Apple', 'SensioLabs', 'Oracle', 'IBM', 'Acquia'];
        shuffle($companies);

        return $companies[0];
    }

    private function getRandomJobTitle()
    {
        $titles = [
            'Joomla Web Developer',
            'Javascript Developer',
            'HTML5/CSS3 Frontend Developer',
            'PHP/Symfony Backend Developer',
            'iOS/Swift developer',
            'Java Androïd Developer',
            'Drupal 8 Script Kiddy',
        ];
        shuffle($titles);
        
        return $titles[0];
    }

    private function getRandomLocation()
    {
        $locations[] = [ 'city' => 'Paris', 'state' => null, 'country' => 'France'];
        $locations[] = [ 'city' => 'Montréal', 'state' => 'QC', 'country' => 'Canada'];
        $locations[] = [ 'city' => 'Zurich', 'state' => null, 'country' => 'Switzerland'];
        $locations[] = [ 'city' => 'New York', 'state' => 'NY', 'country' => 'United States'];
        $locations[] = [ 'city' => 'Los Angeles', 'state' => 'CA', 'country' => 'United States'];
        $locations[] = [ 'city' => 'San Francisco', 'state' => 'CA', 'country' => 'United States'];

        $key = array_rand($locations);

        return $locations[$key];
    }

    private function getRandomPosition()
    {
        $positions = [JobOffer::FREELANCE, JobOffer::FULL_TIME, JobOffer::PART_TIME, JobOffer::TEMPORARY];

        shuffle($positions);

        return $positions[0];
    }

    private function getRandomEmailAddress()
    {
        $emails = [ 'john.doe@example.com', 'foo.bar@localhost.net', 'noreply@hello.com'];

        shuffle($emails);

        return $emails[0];
    }
}
