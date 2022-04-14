<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class WalkerController extends AbstractController
{
    public function __construct(private CacheInterface $cache) {
    }

    /**
     * @return Response
     */
    #[Route('/', name: 'homepage')]
    public function homepage(PostRepository $postRepository): Response {
        $posts = $this->cache->get('homepage', function () use ($postRepository) {
            return $postRepository->findAllPostsWithPoster(9, 0);
        });

        return $this->render('walker/homepage/homepage.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/faq', name: 'faq')]
    public function faq(FaqRepository $faqRepository): Response {
        $faq = $this->cache->get('homepage', function () use ($faqRepository) {
            return $faqRepository->findAll();
        });

        return $this->render('walker/faq/faq.html.twig', [
            'faqs' => $faq
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
