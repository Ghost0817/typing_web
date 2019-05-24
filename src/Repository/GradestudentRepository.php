<?php

namespace App\Repository;

use App\Entity\Gradestudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GradestudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gradestudent::class);
    }

    /**
     * @param $teacher
     * @return Gradestudent[]
     */
    public function findClassStudent($teacher)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a.id gaGroupID, a.name, count(b.id) users FROM App:Grade a
            LEFT JOIN App:GradeStudent b WITH a.id = b.grade
            where a.teacher = :teacher
            group by a.id, a.name
            "
        )->setParameter('teacher', $teacher);

        $products = $query->getResult();

        return $products;
    }

    /**
     * @param $teacher
     * @return Gradestudent[]
     */
    public function findUngroupedStudent($teacher, $txt_ungrouped_students)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT 'ungrouped' as gaGroupID, '" . $txt_ungrouped_students . "' as name, count(b.id) users
            FROM App:GradeStudent b
            WHERE b.grade is null AND b.teacher = :teacher
            "
        )->setParameter('teacher', $teacher);

        $products = $query->getResult();

        return $products;
    }

    /**
     * @param $teacher
     * @param $student
     * @return Gradestudent[]
     */
    public function findStudent($teacher, $student)
    {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('st')
            ->where('st.teacher = :teacher AND st.student = :student')
            ->setParameter('teacher', $teacher)
            ->setParameter('student', $student)
        ;

        try {
            return $query->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $teacher
     * @param $class
     * @return Gradestudent[]
     */
    public function findStudentClass($teacher, $class = null)
    {
        $em = $this->getEntityManager();
        if($class == null || $class == 'all') {
            $query = $em->createQuery(
                "
                SELECT s.id userID, case s.role when 'ROLE_FREE' then 'Standard' when 'ROLE_PREMIUM' then 'Premium' else 'Standard' end userType, s.username, '' password, s.firstname, s.lastname, s.email, s.lastlogin lastLogin, s.created signupDate, g.id gaGroupID, COALESCE(g.name, 'Ungrouped Students') class FROM App:GradeStudent gs
                INNER JOIN App:Student s WITH gs.student = s.id
                LEFT JOIN App:Grade g WITH gs.grade = g.id
                where gs.teacher = :teacher
                "
            )->setParameter('teacher', $teacher);
        } else {

            if($class == 'ungrouped'){
                $query = $em->createQuery(
                    "
                    SELECT s.id userID, case s.role when 'ROLE_FREE' then 'Standard' when 'ROLE_PREMIUM' then 'Premium' else 'Standard' end userType, s.username, '' password, s.firstname, s.lastname, s.email, s.lastlogin lastLogin, s.created signupDate, g.id gaGroupID, COALESCE(g.name, 'Ungrouped Students') class FROM App:GradeStudent gs
                    INNER JOIN App:Student s WITH gs.student = s.id
                    LEFT JOIN App:Grade g WITH gs.grade = g.id
                    where gs.teacher = :teacher and gs.grade is null
                    "
                )->setParameter('teacher', $teacher);
            } else {
                $query = $em->createQuery(
                    "
                    SELECT s.id userID, case s.role when 'ROLE_FREE' then 'Standard' when 'ROLE_PREMIUM' then 'Premium' else 'Standard' end userType, s.username, '' password, s.firstname, s.lastname, s.email, s.lastlogin lastLogin, s.created signupDate, g.id gaGroupID, COALESCE(g.name, 'Ungrouped Students') class FROM App:GradeStudent gs
                    INNER JOIN App:Student s WITH gs.student = s.id
                    LEFT JOIN App:Grade g WITH gs.grade = g.id
                    where gs.teacher = :teacher and gs.grade = :class
                    "
                )->setParameter('teacher', $teacher)->setParameter('class', $class);
            }
        }

        $products = $query->getResult();

        return $products;
    }

    /**
     * @param $teacher
     * @return Gradestudent[]
     */
    public function findUpgradeClassesList($teacher)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a.id, a.name FROM App:Grade a
            INNER JOIN App:GradeStudent b WITH a.id = b.grade
            where b.teacher = :teacher
            group by a.id, a.name
            "
        )->setParameter('teacher', $teacher);

        $products = $query->getResult();

        return $products;
    }
}
