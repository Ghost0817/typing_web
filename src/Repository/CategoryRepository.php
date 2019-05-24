<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param $lang
     * @param $student
     * @return Category[]
     */
    public function findViewCategory( $lang, $student = null)
    {
        $em = $this->getEntityManager();

        $query = null;
        if ( $student != null ){

          $isUpgarded = $em->createQuery(
              "
              SELECT u
              FROM App:UpgradedUsers u
              WHERE u.student = :student AND ( CURRENT_DATE() BETWEEN u.tranDate AND u.expDate or u.upgrade = 'platinum' )
              "
          )->setParameter('student', $student);

          try {

              if($isUpgarded->getSingleResult()){
                $query = $em->createQuery(
                    "
                    SELECT c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug, count(e.id) ae, count(s.exercise) te
                    FROM App:Category c
                    inner join App:Lesson l WITH c.id = l.cate
                    inner join App:Exercise e WITH l.id = e.lesson
                    left join App:Statustyping s WITH e.id = s.exercise AND s.student = :student
                    WHERE c.lang = :lang AND c.id not in ( '13', '14')
                    group by c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug
                    order by c.sortnum
                    "
                )->setParameter('student', $student);
              } else {
                $query = $em->createQuery(
                    "
                    SELECT c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug, count(e.id) ae, count(s.exercise) te
                    FROM App:Category c
                    inner join App:Lesson l WITH c.id = l.cate
                    inner join App:Exercise e WITH l.id = e.lesson
                    left join App:Statustyping s WITH e.id = s.exercise AND s.student = :student
                    WHERE c.lang = :lang AND c.id not in ( '13', '14')
                    group by c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug
                    order by c.sortnum
                    "
                )->setParameter('student', $student);
              }
          } catch (\Doctrine\ORM\NoResultException $e) {
            $query = $em->createQuery(
                "
                SELECT c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug, count(e.id) ae, count(s.exercise) te
                FROM App:Category c
                inner join App:Lesson l WITH c.id = l.cate
                inner join App:Exercise e WITH l.id = e.lesson
                left join App:Statustyping s WITH e.id = s.exercise AND s.student = :student
                WHERE c.lang = :lang AND c.id not in ( '13', '14')
                group by c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug
                order by c.sortnum
                "
            )->setParameter('student', $student);
          }

        } else {
          $query = $em->createQuery(
              "
              SELECT c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug, count(e.id) ae, 0 te
              FROM App:Category c
              inner join App:Lesson l WITH c.id = l.cate
              inner join App:Exercise e WITH l.id = e.lesson
              WHERE c.lang = :lang AND c.id not in ('13','14')
              group by c.id, c.mnTitle, c.enTitle, c.sortnum, c.isPremium, c.slug
              order by c.sortnum
              "
          );
        }
        $query = $query->setParameter('lang', $lang);

        $products = $query->getResult();

        return $products;
    }
}
