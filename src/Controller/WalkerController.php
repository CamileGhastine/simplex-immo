<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalkerController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('walker/homepage.html.twig', [
            'controller_name' => 'WalkerController',
        ]);
    }
}
