<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CategoryRepository;
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
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/post', name: 'index', methods: ["GET"])]
    public function index(Request $request, CategoryRepository $categoryRepository): Response {
        $page = (int)$request->query->get('page') > 0 ? (int)$request->query->get('page') : 1;

        return $this->render('walker/post/index.html.twig', [
            'posts' => $this->paginator->paginate($this->postRepository, 'findAllPostsWithPoster', [
                'page' => $page,
                'maxResultsPerPage' => 10
            ]),
            'renderPagination' => $this->paginator->render(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param int $id
     * @return Response
     */
    #[Route('/post/category/{id<[0-9]+>}', name: 'index_by_category', methods: ["GET"])]
    public function indexByCategory(Request $request, CategoryRepository $categoryRepository, int $id): Response {
        $page = (int)$request->query->get('page') > 0 ? (int)$request->query->get('page') : 1;

        return $this->render('walker/post/index.html.twig', [
            'posts' => $this->paginator->paginate($this->postRepository, 'findAllPostsByCategoryWithPoster', [
                'page' => $page,
                'maxResultsPerPage' => 10,
                'id' => $id
            ]),
            'renderPagination' => $this->paginator->render(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param int $id
     * @return Response
     */
    #[Route('/post/{id<[0-9]+>}', name: 'show', methods: ["GET"])]
    public function show(int $id): Response {
        return $this->render('walker/post/show.html.twig', [
            'post' => $this->postRepository->find($id)
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/faq', name: 'faq')]
    public function faq(): Response {
        return $this->render('walker/faq/faq.html.twig');
    }

    /**
     * @return Response
     */
    #[Route('/prices', name: 'prices')]
    public function prices(): Response {
        return $this->render('walker/faq/prices.html.twig');
    }
}
