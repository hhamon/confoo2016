<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WebAccount;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadWebAccountsData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $user1 = WebAccount::createUserAccount('hhamon');
        $user2 = WebAccount::createCompanyAccount('sensiolabs');
        $user3 = WebAccount::createAdminAccount('superadmin');

        $user1->changePassword($encoder->encodePassword($user1, 'secret123'));
        $user2->changePassword($encoder->encodePassword($user2, 'changeme'));
        $user3->changePassword($encoder->encodePassword($user3, 'superadmin'));

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
