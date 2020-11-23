<?php

namespace App\Repository;

use App\Entity\PchApiCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PchApiCache|null find($id, $lockMode = null, $lockVersion = null)
 * @method PchApiCache|null findOneBy(array $criteria, array $orderBy = null)
 * @method PchApiCache[]    findAll()
 * @method PchApiCache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PchApiCacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PchApiCache::class);
    }
}
