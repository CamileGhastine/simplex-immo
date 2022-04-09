<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalkerController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('walker/homepage.html.twig', [
            'posts' => $postRepository->findAllPostsWithPoster(9, 0)
        ]);
    }
}
