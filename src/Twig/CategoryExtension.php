<?php

namespace App\Twig;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    public function __construct(private EntityManagerInterface $entityManager, private cacheInterface $cache) {
    }

    public function getFunctions(): array {
        return [
            new TwigFunction('category', [$this, 'getCategories']),
        ];
    }

    public function getCategories() {
        $categories = $this->cache->get('categories', function (ItemInterface $item) {
            $item->expiresAfter(3600 * 24 * 7);

            return $this->entityManager->getRepository(Category::class)->findAll();
        });

        return $categories;
    }
}
