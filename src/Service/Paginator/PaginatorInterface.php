<?php

namespace App\Service\Paginator;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

interface PaginatorInterface
{
    /**
     * @param int $page
     */
    public function paginate(ServiceEntityRepository $repository, string $action, array $parameters): array;

    /**
     * @return string
     */
    public function render(int $numberPagesPerRender): ?string;
}
