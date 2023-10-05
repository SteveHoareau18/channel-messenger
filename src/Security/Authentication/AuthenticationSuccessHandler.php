<?php

namespace App\Security\Authentication;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        /** @var \App\Entity\User $user */
        $user = $token->getUser();

        $this->updateLastLoginDate($user);

        return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
    }

    /**
     * @throws \Exception
     */
    protected function updateLastLoginDate(User $user): void
    {
        $user->setLastConnectionDate(new DateTime("now", new \DateTimeZone($_ENV['DATETIMEZONE'])));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
