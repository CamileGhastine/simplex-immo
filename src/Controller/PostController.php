<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Service\Paginator\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository, private PaginatorInterface $paginator, private CacheInterface $cache) {
    }

    /**
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    #[Route('/post', name: 'index', methods: ["GET"])]
    #[Route('/post/category/{id<[0-9]+>}', name: 'index_by_category', methods: ["GET"])]
    public function index(Request $request, int $id = null): Response {

        $page = (int)$request->query->get('page') > 0 ? (int)$request->query->get('page') : 1;

        $cachePosts = $this->cache->get('post-index' . $page . '-' . $id, function () use ($page, $id) {
            $action = $id ? 'findAllPostsByCategoryWithPoster' : 'findAllPostsWithPoster';

            $posts = $this->paginator->paginate($this->postRepository, $action, [
                'page' => $page,
                'maxResultsPerPage' => 10,
                'id' => $id
            ]);

            $renderPagination = $this->paginator->render();

            return [
                'posts' => $posts,
                'renderPagination' => $renderPagination
            ];
        });

        return $this->render('walker/post/index.html.twig', [
            'posts' => $cachePosts['posts'],
            'renderPagination' => $cachePosts['renderPagination']
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
        $post = $this->cache->get('post-show' . $id, function () use ($id) {
            return $this->postRepository->findOneWithCategoryImagesVideos($id);
        });

        return $this->render('walker/post/show.html.twig', [
            'post' => $post
        ]);
    }
}
