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
        if(!$this->getUser()) return $this->redirectToRoute("auth_login");
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

    /**
     * @throws \Exception
     */
    #[Route('/list', name: 'app_channel_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()) return $this->redirectToRoute("auth_login");
        return $this->render('channel/list.html.twig', [
            'channels'=>$entityManager->getRepository(Channel::class)->findBy(['owner'=>$this->getUser()])
        ]);
    }

    #[Route('/details/{id}', name: 'app_channel_see')]
    public function see(EntityManagerInterface $entityManager, Channel $channel): Response
    {
        if(!$this->getUser()) return $this->redirectToRoute("auth_login");
        return $this->render('channel/see.html.twig', [
            'channel'=>$channel
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/modify/{id}', name: 'app_channel_modify')]
    public function modify(Request $request, EntityManagerInterface $entityManager, Channel $channel): Response
    {
        if(!$this->getUser()) return $this->redirectToRoute("auth_login");
        if($channel->getOwner()->getUserIdentifier() != $this->getUser()->getUserIdentifier()) return $this->redirectToRoute("app_dashboard");
        $form = $this->createForm(ChannelType::class,$channel);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($channel);
            $entityManager->flush();//todo add flashbag

            return $this->redirectToRoute('app_dashboard');
        }
        return $this->render('channel/modify.html.twig', [
            'form'=>$form
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/delete/{id}', name: 'app_channel_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager, Channel $channel): Response
    {
        if($channel->getOwner()->getUserIdentifier() != $this->getUser()->getUserIdentifier()) return $this->redirectToRoute("app_dashboard");
        $entityManager->remove($channel);
        $entityManager->flush();//todo add flashbag

        return $this->redirectToRoute('app_dashboard');
    }
}
