<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalkerController extends AbstractController
{
    public function __construct(
        private PostRepository $postRepository,
    ){}

    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        return $this->render('walker/homepage.html.twig', [
            'posts' => $this->postRepository->findAllPostsWithPoster(9, 0)
        ]);
    }

    #[Route('/post', name: 'index')]
    public function indexNews(): Response
    {
        return $this->render('walker/posts/index.html.twig', [
            'posts' => $this->postRepository->findAllPostsWithPoster()
        ]);
    }
}
