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
            ->addSelect('media')
            ->leftJoin('post.medias', 'media')
            ->where('media.poster = 1')
            ->orWhere('media.poster IS NULL')
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
    public function findAllPostsByCategoryWithPoster(int $id, $maxResult = null, $firstResult = null) {
        return $this->createQueryBuilder('post')
            ->addSelect('media')
            ->addSelect('category')
            ->innerJoin('post.category', 'category')
            ->leftJoin('post.medias', 'media')
            ->where('category.id = :id')
            ->andWhere('media.poster = 1 OR media.poster IS NULL')
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

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
