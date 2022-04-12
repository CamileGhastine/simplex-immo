<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use App\Repository\PostRepository;
use App\Service\Paginator\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalkerController extends AbstractController
{
    public function __construct(private PostRepository $postRepository, private PaginatorInterface $paginator) {
    }

    /**
     * @return Response
     */
    #[Route('/', name: 'homepage')]
    public function homepage(): Response {
        return $this->render('walker/homepage/homepage.html.twig', [
            'posts' => $this->postRepository->findAllPostsWithPoster(9, 0)
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/faq', name: 'faq')]
    public function faq(FaqRepository $faqRepository): Response {
        return $this->render('walker/faq/faq.html.twig', [
            'faqs' => $faqRepository->findAll()
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/prices', name: 'prices')]
    public function prices(): Response {
        return $this->render('walker/prices/prices.html.twig');
    }
}
