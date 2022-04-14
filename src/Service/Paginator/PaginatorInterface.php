<?php

namespace App\Service\Paginator;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

interface PaginatorInterface
{
    /**
     * @param ServiceEntityRepository $repository
     * @param string $action
     * @param array $parameters
     * @return array
     */
    public function paginate(ServiceEntityRepository $repository, string $action, array $parameters): array;

    /**
     * @param int $numberPagesPerRender
     * @return string|null
     */
    public function render(int $numberPagesPerRender): ?string;
}
