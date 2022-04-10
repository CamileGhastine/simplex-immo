<?php

namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

interface PaginatorInterface
{
    /**
     * @param string $action
     * @param int $page
     * @return array
     */
    public function paginate(ServiceEntityRepository $repository, string $action, int $page, int $maxResult): array;

    /**
     * @return string
     */
    public function render(int $numberPagesPerRender): string;
}
