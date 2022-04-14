<?php

namespace App\Tests\Service;

use App\Service\Paginator\BootstrapPaginator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

// ⚠️To play this tests, set the constant NB_POSTS = 100 in App\DataFixtures\PostFixtures;

class BootstrapPaginatorTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    private $databaseTool;
    private $paginator;
    private $maxResultsPerPage;
    private $paramters;

    public function setUp(): void
    {
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->repository = $this->getContainer()->get('doctrine')->getRepository('App:Post');
        $this->databaseTool->loadFixtures(['App\DataFixtures\PostFixtures']);

        $this->maxResultsPerPage = 8;
        $this->parameters = [
            'page' => 1,
            'maxResultsPerPage' => $this->maxResultsPerPage,
            'id' => null
        ];
        $this->paginator = new BootstrapPaginator();
    }


    public function testNumberOfResultsPerpageIndexPage1()
    {
        $posts = $this->paginator->paginate($this->repository, 'findAllPostsWithPoster', $this->parameters);

        $this->assertEquals($this->maxResultsPerPage, count($posts));
    }

    public function testNumberOfResultsPerpageIndexPage3()
    {
        $this->parameters ['page'] = 3;

        $posts = $this->paginator->paginate($this->repository, 'findAllPostsWithPoster', $this->parameters);

        $this->assertEquals($this->maxResultsPerPage, count($posts));
    }

    public function testNumberOfResultsPerpageIndexByCategoryPage1()
    {
        $this->parameters['id'] = 1;

        $postsExpected = $this->repository->findAllPostsByCategoryWithPoster(1, 8, 0);
        $posts = $this->paginator->paginate($this->repository, 'findAllPostsByCategoryWithPoster', $this->parameters);

        $this->assertEquals($postsExpected, $posts);
    }

    public function testNumberOfResultsPerpageIndexByCategoryPage2()
    {

        $this->parameters['page'] = 2;
        $this->parameters['id'] = 1;

        $postsExpected = $this->repository->findAllPostsByCategoryWithPoster(1, 8, 8);
        $posts = $this->paginator->paginate($this->repository, 'findAllPostsByCategoryWithPoster', $this->parameters);

        $this->assertEquals($postsExpected, $posts);
    }
}
