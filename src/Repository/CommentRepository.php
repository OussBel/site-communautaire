<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**

     * @return PaginatedComments[]
     */
    public function pagination(int $page, string $slug, int $limit = 10): array
    {

        $limit = abs($limit);

        $result = [];

        $query =  $this->createQueryBuilder('c')
            ->join('c.trick', 't')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->setFirstResult( $page * $limit  - $limit)
            ->setMaxResults($limit)
            ->orderBy('c.createdAt', 'DESC');

        $paginator = new Paginator($query);

        $data = $paginator->getQuery()->getResult();

        if(empty($data)) {
            return $result;
        }

        $pages = ceil($paginator->count()/ $limit);

        $result['data'] = $data;
        $result['limit'] = $limit;
        $result['page'] = $page;
        $result['pages'] = $pages;

        return $result;

    }

//    /**
//     * @return Comment[] Returns an array of Comment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}