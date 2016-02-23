<?php

namespace AppBundle\Security;

use AppBundle\Entity\WebAccount;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class LoginFormGuardAuthenticator extends AbstractGuardAuthenticator
{
    private $router;
    private $encoder;
    private $entityManager;

    public function __construct(
        UrlGeneratorInterface $router,
        UserPasswordEncoderInterface $encoder,
        ObjectManager $entityManager
    )
    {
        $this->router = $router;
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function getCredentials(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_POST)
            || 'app_login_check' !== $request->attributes->get('_route')) {
            return;
        }

        $username = $request->request->get('my_username');
        $password = $request->request->get('my_password');

        if (!$username || !$password) {
            throw new CustomUserMessageAuthenticationException('Missing username or password parameter.');
        }

        return ['username' => $username, 'password' => $password];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->encoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $session = $request->getSession();
        if ($session) {
            $session->set(Security::LAST_USERNAME, $request->request->get('my_username'));
            $session->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return $this->start($request, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $session = $request->getSession();
        if ($session) {
            $session->remove(Security::LAST_USERNAME);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        $user = $token->getUser();
        if ($user instanceof WebAccount) {
            $user->recordLastLoginTime();
            $this->entityManager->flush($user);
        }

        return new RedirectResponse($this->router->generate('app_homepage'));
    }

    public function supportsRememberMe()
    {
        return true;
    }
}
