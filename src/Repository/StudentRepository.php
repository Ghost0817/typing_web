<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class StudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function findAddAccount( $username, $password)
    {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('st')
            ->where('st.username = :user AND st.password = :pass')
            ->setParameter('user', $username)
            ->setParameter('pass', $password)
        ;

        try {
            return $query->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findAccount( $username )
    {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('st')
            ->where('st.username = :user')
            ->setParameter('user', $username)
        ;

        try {
            return $query->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findOnlineAccount( $teacher )
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a FROM App:Student a
            INNER JOIN App:GradeStudent b WITH a.id = b.student 
            where b.teacher = :teacher and (TIMESTAMPDIFF(SECOND, a.lastActivityAt, now()) - 3600) <= 900
            "
        )->setParameter('teacher', $teacher);

        $students = $query->getResult();

        return $students;
    }

    public function findOfflineAccount( $teacher )
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a.id, a.lastAction,TO_SECONDS( a.lastActivityAt) - 3600  FROM App:Student a
            INNER JOIN App:GradeStudent b WITH a.id = b.student
            where b.teacher = :teacher and (TIMESTAMPDIFF(SECOND, a.lastActivityAt, now()) - 3600) > 900
            "
        )->setParameter('teacher', $teacher);

        $students = $query->getResult();

        return $students;
    }

    public function findAllAccount( $teacher )
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a FROM App:Student a
            INNER JOIN App:GradeStudent b WITH a.id = b.student
            where b.teacher = :teacher
            "
        )->setParameter('teacher', $teacher);

        $students = $query->getResult();

        return $students;
    }

    public function findUpgradeList( $teacher )
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a.id, a.username, a.firstname, a.lastname, a.lastlogin, IDENTITY(b.grade) grade FROM App:Student a
            LEFT JOIN App:GradeStudent b WITH a.id = b.student
            where b.teacher = :teacher
            "
        )->setParameter('teacher', $teacher);

        $students = $query->getResult();

        return $students;
    }
}
