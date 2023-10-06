<?php

namespace App\Controller;

use App\Entity\ChannelUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()) return $this->redirectToRoute('auth_login');
        $other_channels = $entityManager->getRepository(ChannelUser::class)->findBy(['user'=>$this->getUser()]);
        return $this->render('index.html.twig', [
            'other_channels'=>$other_channels
        ]);
    }
}
