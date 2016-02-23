<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Table(name="job_web_accounts", uniqueConstraints={
 *   @UniqueConstraint(
 *     name="web_account_username_unique",
 *     columns="username"
 *   )
 * })
 * @Entity
 */
class WebAccount implements UserInterface
{
    /**
     * @Column(type="smallint")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(length=20)
     */
    private $type;

    /**
     * @Column(length=25)
     */
    private $username;

    /**
     * @Column
     */
    private $password;

    /**
     * @Column(length=30)
     */
    private $profile;

    /**
     * @Column(type="datetime", nullable=true)
     */
    private $lastLoginTime;

    public function __construct($username, $password = null, $type = 'INDIVIDUAL', $profile = 'USER')
    {
        $this->username = $username;
        $this->password = $password;
        $this->type = $type;
        $this->profile = $profile;
    }

    public function recordLastLoginTime()
    {
        $this->lastLoginTime = new \DateTime();
    }

    public static function createUserAccount($username)
    {
        return new self($username);
    }

    public static function createCompanyAccount($username)
    {
        return new self($username, null, 'COMPANY', 'USER');
    }

    public static function createAdminAccount($username)
    {
        return new self($username, null, 'INDIVIDUAL', 'ADMIN');
    }

    public function changePassword($encodedPassword)
    {
        $this->password = $encodedPassword;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function getRoles()
    {
        $roles = [];
        if ('COMPANY' === $this->type) {
            $roles[] = new Role('ROLE_COMPANY');
            return $roles;
        }

        if ('ADMIN' === $this->profile) {
            $roles[] = new Role('ROLE_ADMIN');
            return $roles;
        }
        
        $roles[] = new Role('ROLE_USER');

        return $roles;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
