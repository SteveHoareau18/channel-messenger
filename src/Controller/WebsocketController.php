<?php

namespace App\Controller;

use App\Tools\WebsocketPush;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
// /!\ Controller de test
class WebsocketController extends AbstractController
{
    #[Route('/websocket', name: 'app_websocket')]
    public function websocket()
    {
        WebsocketPush::channel("123e4567-e89b-12d3-a456-426614174000");
    }
}