<?php

namespace App\Service\Paginator;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class BootstrapPaginator implements PaginatorInterface
{
    public const MAX_RESULT_PER_PAGE = 10;
    public const NUMBER_PAGES_PER_RENDER = 5;
    private int $maxResultsPerPage;
    private int $page;
    private int $numberOfResults;
    private array $results;
    private int $numberOfPages;
    private int $firstpage;
    private int $lastpage;

    /**
     * Return posts paginated
     * @param ServiceEntityRepository $repository
     * @param string $action
     * @param array $parameters
     * @return array
     */
    public function paginate(ServiceEntityRepository $repository, string $action, array $parameters): array
    {
        $this->hydrate($repository, $action, $parameters);

        return $this->results;
    }

    /**
     * Return an HTML pagination
     * @param int $id
     * @param int $page
     *@return string
     */
    public function render(int $numberPagesPerRender = self::NUMBER_PAGES_PER_RENDER): ?string
    {
        $this->setParameter($numberPagesPerRender);

        if (0 === $this->numberOfPages) {
            return null;
        }

        $disabled = 1 === $this->page ? 'disabled' : null;
        $render = '<ul class="pagination pagination-sm">
                        <li class="page-item ' . $disabled . '">
                            <a class="page-link" href="?page=1">&laquo;</a>
                        </li>';

        for ($i = $this->firstpage; $i <= $this->lastpage; ++$i) {
            $active = $i === $this->page ? 'active' : null;
            $render .= '<li class="page-item ' . $active . '">
                            <a class="page-link" href="?page=' . $i . '"> ' . $i . '</a>
                        </li>';
        }

        $disabled = $this->page === $this->numberOfPages ? 'disabled' : null;
        $render .= '<li class="page-item ' . $disabled . '">
                        <a class="page-link" href="?page=' . $this->numberOfPages . '">&raquo;</a>
                    </li>
                </ul >';

        return $render;
    }

    /**
     * @param int $page
     * @param int $maxResultsPerPage
     */
    private function hydrate(ServiceEntityRepository $repository, string $action, array $parameters): void
    {
        extract($parameters);

        $id = isset($id) ? $id : null;
        $this->numberOfResults = count($repository->$action($id, null, null));
        $maxResultsPerPage = isset($maxResultsPerPage) ? $maxResultsPerPage: self::MAX_RESULT_PER_PAGE;
        $this->numberOfPages = (int)ceil($this->numberOfResults / $maxResultsPerPage);
        $page = $page > 0 ? $page : 1;
        $this->page = $page > $this->numberOfPages ? $this->numberOfPages : $page;

        $this->results = $repository->$action($id, $maxResultsPerPage, ($this->page - 1) * $maxResultsPerPage);
    }

    /**
     * @return void
     */
    private function setParameter(int $numberPagesPerRender)
    {
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
