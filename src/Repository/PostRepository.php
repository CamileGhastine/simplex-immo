<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param null $maxResult
     * @param null $firstResult
     *
     * @return int|mixed|string
     */
    public function findAllPostsWithPoster($maxResult = null, $firstResult = null) {
        return $this->createQueryBuilder('post')
            ->addSelect('image')
            ->leftJoin('post.images', 'image')
            ->where('image.poster = 1')
            ->orWhere('image.poster IS NULL')
            ->orderBy('post.updatedAt', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $maxResult
     * @param $firstResult
     *
     * @return float|int|mixed|string
     */
    public function findAllPostsByCategoryWithPoster($maxResult = null, $firstResult = null, int $id) {
        return $this->createQueryBuilder('post')
            ->addSelect('image')
            ->addSelect('category')
            ->innerJoin('post.category', 'category')
            ->leftJoin('post.images', 'image')
            ->where('category.id = :id')
            ->andWhere('image.poster = 1 OR image.poster IS NULL')
            ->setParameter('id', $id)
            ->orderBy('post.updatedAt', 'DESC')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult)
            ->getQuery()
            ->getResult();
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Post $entity, bool $flush = true): void {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Post $entity, bool $flush = true): void {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findOneWithCategoryImagesVideos(int $id): ?Post
    {
        return $this->createQueryBuilder('post')
            ->addSelect('category')
            ->addSelect('image')
            ->addSelect('video')
            ->leftJoin('post.category', 'category')
            ->leftJoin('post.images', 'image')
            ->leftJoin('post.videos', 'video')
            ->where('post.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }

}
