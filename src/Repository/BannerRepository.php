<?php

namespace App\Repository;

use App\Entity\Banner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BannerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Banner::class);
    }

    /**
     * @param $position
     * @return Banner[]
     */
    public function findBannerBySysdate($position)
    {
        $em = $this->getEntityManager();
        $now = new \DateTime();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('b')
            ->from('App:Banner', 'b')
            ->where('b.placingId = :place')
            ->andWhere('b.fromdate <= :now OR b.fromdate IS NULL')
            ->andWhere('b.todate >= :now OR b.todate IS NULL')
            ->setParameter('place', $position)
            ->setParameter('now', $now)
        ;
        $result = $qb->getQuery()->execute();
        if (count($result) > 1) {
            $entities = array();
            foreach ($result as $item) {
                $entities[] = $item;
            }
            $entity = $entities[array_rand($entities)];
        } else {
            $entity = current($result);
        }
        return $entity;
    }
}
