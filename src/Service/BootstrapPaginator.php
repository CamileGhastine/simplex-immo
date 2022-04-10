<?php

namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class BootstrapPaginator implements PaginatorInterface
{
    const MAX_RESULT_PER_PAGE = 10;
    const NUMBER_PAGES_PER_RENDER = 5;
    private int $maxResultsPerPage;
    private int $page;
    private int $numberOfResults;
    private array $results;
    private int $numberOfPages;
    private int $firstpage;
    private int $lastpage;


    /**
     * @param int $id
     * @param int $page
     * @return array
     */
    public function paginate(ServiceEntityRepository $repository, string $action, int $page = 1, int $maxResultsPerPage = self::MAX_RESULT_PER_PAGE): array {
        $this->hydrate($repository, $action, $page, $maxResultsPerPage);

        return $this->results;
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return string
     */
    public function render(int $numberPagesPerRender = self::NUMBER_PAGES_PER_RENDER): string {
        $this->setParameter($numberPagesPerRender);

        $disabled = $this->page === 1 ? 'disabled' : null;
        $render = '<ul class="pagination pagination-sm"><li class="page-item ' . $disabled . '"><a class="page-link" href="?page=1">&laquo;</a></li>';

        for ($i = $this->firstpage; $i <= $this->lastpage; ++$i) {
            $active = $i === $this->page ? 'active' : null;
            $render .= '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '"> ' . $i . '</a></li>';
        }

        $disabled = $this->page === $this->numberOfPages ? 'disabled' : null;
        $render .= '<li class="page-item ' . $disabled . '"><a class="page-link" href="?page=' . $this->numberOfPages . '">&raquo;</a></li></ul >';

        return $render;
    }

    /**
     * @param ServiceEntityRepository $repository
     * @param string $action
     * @param int $page
     * @param int $maxResultsPerPage
     *
     * @return void
     */
    private function hydrate(ServiceEntityRepository $repository, string $action, int $page, int $maxResultsPerPage): void {
        $this->maxResultsPerPage = $maxResultsPerPage;
        $this->page = $page > 0 ? $page : 1;
        $this->numberOfResults = count($repository->$action());
        $this->results = $repository->$action($maxResultsPerPage, ($page - 1) * $maxResultsPerPage);
    }

    /**
     * @param int $numberPagesPerRender
     *
     * @return void
     */
    private function setParameter(int $numberPagesPerRender) {
        $this->numberOfPages = (int)ceil($this->numberOfResults / $this->maxResultsPerPage);

        $numberPagesPerRender = $this->numberOfPages < $numberPagesPerRender
            ? $this->numberOfPages
            : $numberPagesPerRender;

        $this->firstpage = $this->page <= ($numberPagesPerRender / 2)
            ? 1
            : $this->page - (int)($numberPagesPerRender / 2) + (int)!($numberPagesPerRender % 2);
        $this->firstpage = $this->page > ($this->numberOfPages - (int)($numberPagesPerRender / 2))
            ? $this->numberOfPages - $numberPagesPerRender + 1
            : $this->firstpage;

        $this->lastpage = $this->page > $this->numberOfPages - (int)($numberPagesPerRender / 2)
            ? $this->numberOfPages
            : $this->firstpage + $numberPagesPerRender - 1;
    }
}

