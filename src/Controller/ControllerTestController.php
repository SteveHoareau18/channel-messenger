<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerTestController extends AbstractController
{
    #[Route('/controller/test', name: 'app_controller_test')]
    public function index(): Response
    {
        return $this->render('controller_test/index.html.twig', [
            'controller_name' => 'ControllerTestController',
        ]);
    }
}
