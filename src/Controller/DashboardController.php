<?php

namespace App\Controller;

use App\Entity\ChannelUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/api/filter/user-list', name: 'api_filter_user_list',methods: ['GET'])]
    public function apiFilterUserList(Request $request, UserRepository $userRepository): JsonResponse{
        if(!$request->query->has('query') || $request->query->get('query') == "") return new JsonResponse(array());
        $query = $request->query->get('query');
        $usernames = $userRepository->findByWithUsernameLike($query);
        $response = array();
        foreach ($usernames as $username){
            array_push($response,$username->getUsername());
        }
//        $emails =  $userRepository->findByWithEmailLike($query);
//        if($emails != null){
//            array_push($response,$emails);
//        }
        return new JsonResponse($response);
    }
}
