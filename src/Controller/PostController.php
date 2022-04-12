<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\Paginator\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository, private PaginatorInterface $paginator) {
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
            'post' => $this->postRepository->findOneWithCategoryImagesVideos($id)
        ]);
    }
}
