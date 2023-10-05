<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Form\ChannelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/channel')]
class ChannelController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/new', name: 'app_channel_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //todo check login
        $channel = new Channel($this->getUser(),'');
        $form = $this->createForm(ChannelType::class,$channel);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($channel);
            $entityManager->flush();//todo add flashbag

            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('channel/new.html.twig', [
            'form'=>$form
        ]);
    }
}
