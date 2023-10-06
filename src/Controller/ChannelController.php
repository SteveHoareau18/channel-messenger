<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\ChannelUser;
use App\Entity\Type;
use App\Entity\User;
use App\Form\ChannelType;
use App\Form\ChannelUserType;
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
    #[Route('/invite/{id}', name: 'app_channel_index_invite')]
    public function inviteIndex(Request $request, EntityManagerInterface $entityManager, Channel $channel): Response
    {
        $channelUser = new ChannelUser();
        $form = $this->createForm(ChannelUserType::class,$channelUser);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $channelUser->setChannel($channel);
            $user = $entityManager->getRepository(User::class)->findOneBy(['username'=>$form->get('user')->getData()]);
            if($user == null) $user = $entityManager->getRepository(User::class)->findOneBy(['email'=>$form->get('user')->getData()]);
            if($user != null) {
                if($entityManager->getRepository(ChannelUser::class)->findBy(['channel'=>$channel,'user'=>$user])){
                    //todo add flashbag error
                    return $this->redirectToRoute("app_channel_index_invite",['id'=>$channel->getId()]);
                }
                $channelUser->setUser($user);
                $entityManager->persist($channelUser);
                $entityManager->flush();//todo add flashbag

                return $this->redirectToRoute('app_channel_index', ['id' => $channel->getId()]);
            }else{
                //todo add flashbag error
                return $this->redirectToRoute("app_channel_index_invite",['id'=>$channel->getId()]);
            }
        }
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('channel/invite/invite.index.html.twig', [
            'form'=>$form,
            'channel'=>$channel,
            'userList'=>$users
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/c/{id}', name: 'app_channel_index')]
    public function index(EntityManagerInterface $entityManager, Channel $channel): Response
    {
        if(!$this->getUser()) return $this->redirectToRoute("auth_login");

        if($channel->getType() == Type::PRIVATE){
            $channelUser = $entityManager->getRepository(ChannelUser::class)->findOneBy(['channel'=>$channel,'user'=>$this->getUser()]);
            if($channelUser != null ||
                $channel->getOwner()->getUserIdentifier() == $this->getUser()->getUserIdentifier()) {

                if($channelUser != null){
                    if($channelUser->getJoinDate()==null){
                        $channelUser->setJoinDate(new \DateTime("now",new \DateTimeZone($_ENV['DATETIMEZONE'])));
                        $entityManager->persist($channelUser);
                        $entityManager->flush();
                    }
                }

                $other_channels = $entityManager->getRepository(ChannelUser::class)->findBy(['user'=>$this->getUser()]);
                return $this->render('index.html.twig', [
                    'channel' => $channel,
                    'other_channels' => $other_channels
                ]);
            }else{
                return $this->redirectToRoute("app_dashboard");
            }
        }else{
            $other_channels = $entityManager->getRepository(ChannelUser::class)->findBy(['user'=>$this->getUser()]);
            return $this->render('index.html.twig', [
                    'channel' => $channel,
                    'other_channels' => $other_channels
            ]);
        }
    }

    /**
     * @throws \Exception
     */
    #[Route('/new', name: 'app_channel_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()) return $this->redirectToRoute("auth_login");
        $channel = new Channel();
        $form = $this->createForm(ChannelType::class,$channel);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $channel->setOwner($this->getUser());
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
            'form'=>$form,
            'channel'=>$channel
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
