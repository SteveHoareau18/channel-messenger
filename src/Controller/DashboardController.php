<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Message;
use App\Repository\ChannelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ChannelRepository $channelRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $channels = $channelRepository->findAccessibleForUser($user);
        $channel = $channelRepository->findFirstWithMessagesForUser($user);

        return $this->render('index.html.twig', [
            'channels' => $channels,
            'channel' => $channel,
        ]);
    }

    #[Route('/send', name: 'app_dashboard_send_message', methods: ['POST'])]
    public function sendMessage(Request $request, EntityManagerInterface $entityManager)
    {
        $token = $request->request->get('_token');
        $message = new Message();

        if ($this->isCsrfTokenValid('send_message', $token)) {
            $message
                ->setContent($request->request->get('content'))
                ->setSender($this->getUser())
                ->setChannel($entityManager->getReference(Channel::class, $request->request->get('channel_id')));

            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Message envoyé');
        } else {
            $this->addFlash('danger', 'Erreur lors de l\'envoi du message');
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
