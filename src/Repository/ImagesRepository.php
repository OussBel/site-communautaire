<?php

namespace App\Repository;

use App\Entity\Images;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Images>
 *
 * @method Images|null find($id, $lockMode = null, $lockVersion = null)
 * @method Images|null findOneBy(array $criteria, array $orderBy = null)
 * @method Images[]    findAll()
 * @method Images[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Images::class);
    }

    /**
     * @param Trick $trick
     * @return float|int|mixed|string
     */
    public function imagesByTrickId(Trick $trick): mixed
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.trick = :trick')
            ->setParameter('trick', $trick)
            ->getQuery()
            ->getResult();
    }


}
