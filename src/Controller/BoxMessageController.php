<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BoxMessageController extends AbstractController
{
    #[Route('/box/message', name: 'app_box_message')]
    public function index(): Response
    {
        return $this->render('box_message/index.html.twig', [
            'controller_name' => 'BoxMessageController',
        ]);
    }
}
