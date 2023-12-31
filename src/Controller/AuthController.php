<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'auth_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error
        ]);
    }

    #[Route('/register', name: 'auth_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreateDate(new DateTime());
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('auth_login');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/logout', name: 'auth_logout', methods: ['GET'])]
    public function logout(): never
    {
        throw new \Exception('Ooops !');
    }

    #[Route('/me', name: 'auth_me', methods: ['POST'])]
    public function logged(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (empty($email) && empty($password)) {
            return new JsonResponse(['success' => true, "message" => "need credentials"], 200);
        }

        $user = $em->getRepository(User::class)->findOneBy([
            'email' => $request->request->get('email'),
        ]);

        if ($user) {
            if ($passwordHasher->isPasswordValid($user, $password)) {
                return new JsonResponse(['success' => true], 200);
            }
        }

        return new JsonResponse(['success' => false], 200);
    }
}