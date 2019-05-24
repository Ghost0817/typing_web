<?php

namespace App\Repository;

use App\Entity\Lesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class LessonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    public function findViewStudentLessonInfo($lang, $student)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a.id lessonID, b.sortnum, b.mnTitle cmnTitle, b.enTitle cenTitle, a.mnTitle, 
            a.enTitle, '0' active, 0 disabled, '' displayOrder, a.isPremium,
            count(e.id) ae, count(s.exercise) te, SUM(s.time) time, SUM(s.correcthit) correcthit, SUM(s.mistakehit) mistakehit,
            0 netSpeed,0 grossSpeed, AVG(s.acc) accuracy, MAX(s.createdAt) lastTyped
            FROM App:Lesson a
            INNER JOIN App:Category b WITH a.cate = b.id
            INNER JOIN App:Lang c WITH b.lang = c.id
            inner join App:Exercise e WITH a.id = e.lesson
            left join App:Statustyping s WITH e.id = s.exercise and s.student = :student
            where c.id = :lang and b.id not in ( 13, 14 )
            group by a.id, b.sortnum, b.mnTitle, b.enTitle, a.mnTitle, a.enTitle, a.isPremium
            "
        )->setParameter('lang', $lang)->setParameter('student', $student);

        $products = $query->getResult();

        return $products;
    }

    public function findViewLessonInfo($lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a.id lessonID, b.sortnum, b.mnTitle cmnTitle, b.enTitle cenTitle, a.mnTitle, a.enTitle, '0' active, 0 disabled, '' displayOrder, a.isPremium
            FROM App:Lesson a
            INNER JOIN App:Category b WITH a.cate = b.id
            INNER JOIN App:Lang c WITH b.lang = c.id
            where c.id = :lang and b.id not in ( 13, 14 )
            "
        )->setParameter('lang', $lang);

        $products = $query->getResult();

        return $products;
    }

    public function findViewLessonListWithStatus($student)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT l.id, l.mnTitle, l.enTitle, IDENTITY(l.cate) cate, l.sortnum, l.isPremium, l.isShow,
            count(e.id) ae, count(s.exercise) te, SUM(s.time) seconds, SUM(s.correcthit) alltyped, SUM(s.mistakehit) errors,
            0 netSpeed, AVG(s.acc) accuracy
            FROM App:Lesson l
            inner join App:Exercise e WITH l.id = e.lesson
            left join App:Statustyping s WITH e.id = s.exercise and s.student = :student
            group by l.id, l.mnTitle, l.enTitle, l.cate, l.sortnum, l.isPremium, l.isShow
            order by l.sortnum
            "
        )->setParameter('student', $student);

        $products = $query->getResult();

        return $products;
    }

    public function findViewLessonList()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT l.id, l.mnTitle, l.enTitle, IDENTITY(l.cate) cate, l.sortnum, l.isPremium, l.isShow, count(e.id) ae, 0 te
            FROM App:Lesson l
            inner join App:Exercise e WITH l.id = e.lesson
            group by l.id, l.mnTitle, l.enTitle, l.cate, l.sortnum, l.isPremium, l.isShow
            order by l.sortnum
            "
        );

        $products = $query->getResult();

        return $products;
    }
}
