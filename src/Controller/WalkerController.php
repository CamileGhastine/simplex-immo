<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Service\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalkerController extends AbstractController
{
    public function __construct(private PostRepository $postRepository, private PaginatorInterface $paginator) {
    }

    #[Route('/', name: 'homepage')]
    public function homepage(): Response {
        return $this->render('walker/homepage.html.twig', [
            'posts' => $this->postRepository->findAllPostsWithPoster(9, 0)
        ]);
    }

    #[Route('/post', name: 'index', methods: ["GET"])]
    public function indexNews(Request $request): Response {
        $page = (int)$request->query->get('page') > 0 ? (int)$request->query->get('page') : 1;

        return $this->render('walker/posts/index.html.twig', [
            'posts' => $this->paginator->paginate($this->postRepository, 'findAllPostsWithPoster', $page),
            'renderPagination' => $this->paginator->render(6)
        ]);
    }
}
