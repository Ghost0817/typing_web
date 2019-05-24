<?php
/**
 * Created by PhpStorm.
 * User: 22cc3355
 * Date: 1/25/2019
 * Time: 10:00 AM
 */

namespace App\Repository;

use App\Entity\Statustyping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class StatustypingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Statustyping::class);
    }

    public function findCurrentExe($user_id, $lang, $lesson)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT count(a) exerciseNum
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and l.id=:lesson and c.id not in ('13','14')
            "
        )
            ->setParameter('student', $user_id)
            ->setParameter('lang', $lang)
            ->setParameter('lesson', $lesson)
        ;

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findTakenTest($user_id, $lang, $lesson)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT count(l.id) exerciseNum
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and l.id=:lesson and c.id in ('13','14')
            group by l.id
            "
        )
            ->setParameter('student', $user_id)
            ->setParameter('lang', $lang)
            ->setParameter('lesson', $lesson)
        ;

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    public function findSummaryGraph($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT l.id, c.mnTitle cmnTitle, c.enTitle cenTitle, l.mnTitle, l.enTitle,
            SUM(a.time) time, AVG(a.acc) acc,
            SUM(a.correcthit) correcthit,
            SUM(a.mistakehit) mistakehit,
            count(e.id) ce,
            (SELECT count(ex) from App:Exercise ex where ex.lesson = l.id ) ae
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and c.id not in ('13','14')
            group by l.id, l.mnTitle, l.enTitle
            "
        )
            ->setParameter('student', $user_id)
            ->setParameter('lang', $lang)
        ;

        $products = $query->getResult();

        return $products;
    }

    public function findActivityGraph($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $this->createQueryBuilder('st')
            ->select(' SUM(st.time) AS spenttime, DATE(st.createdAt) createdAt, MAX(st.lastlogin) lastlogin ')
            ->innerJoin('App:Exercise', 'ex', 'WITH', 'st.exercise = ex.id')
            ->innerJoin('App:Lesson', 'ln', 'WITH', 'ex.lesson = ln.id')
            ->innerJoin('App:Category', 'ct', 'WITH', 'ln.cate=ct.id')
            ->groupBy('createdAt')
            ->orderBy('createdAt', 'ASC')
            ->where('ct.lang = :lang AND st.student = :stdnt and ct.id not in ( 13, 14)')
            ->setParameter('lang', $lang)
            ->setParameter('stdnt', $user_id)
            ->setMaxResults(10);

        $products = $query->getQuery()->getResult();

        return $products;
    }

    public function findProblemKeyGraph($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT l.id, c.mnTitle cmnTitle, c.enTitle cenTitle, l.mnTitle, l.enTitle,
            a.errorKeys,a.allKeys
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and c.id not in ('13','14')
            "
        )
            ->setParameter('student', $user_id)
            ->setParameter('lang', $lang)
        ;

        $products = $query->getResult();

        return $products;
    }

    public function findTypingTestGraph($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT l.id, c.mnTitle cmnTitle, c.enTitle cenTitle, l.mnTitle, l.enTitle,
            a.time, a.acc acc,
            a.correcthit,
            a.mistakehit, a.createdAt
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and c.id in ('13','14')
            order by a.lastlogin ASC, a.id ASC
            "
        )->setParameter('student', $user_id)->setParameter('lang', $lang);

        $products = $query->getResult();

        return $products;
    }

    public function findTypingTestProgess($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a.id, a.createdAt,a.time, a.acc accuracy,a.correcthit,a.mistakehit, 'N/A' improve, 'N/A' overallimprove
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and c.id in ('13','14')
            order by a.lastlogin ASC, a.id ASC
            "
        )->setParameter('student', $user_id)->setParameter('lang', $lang);

        $products = $query->getResult();

        return $products;
    }

    public function findTypingTestCount($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and c.id in ('13','14')
            order by a.lastlogin ASC, a.id ASC
            "
        )->setParameter('student', $user_id)->setParameter('lang', $lang);

        $products = $query->getResult();

        return $products;
    }

    public function findKeysDel($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and c.id not in ('13','14')
            "
        )
            ->setParameter('student', $user_id)
            ->setParameter('lang', $lang)
        ;

        $products = $query->getResult();

        return $products;
    }

    public function findTestDel($user_id, $lang)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "
            SELECT a
            FROM App:Statustyping a
            inner join App:Exercise e WITH a.exercise = e.id
            inner join App:Lesson l WITH e.lesson = l.id
            inner join App:Category c WITH l.cate = c.id
            where a.student = :student and c.lang = :lang and c.id in ('13','14')
            "
        )
            ->setParameter('student', $user_id)
            ->setParameter('lang', $lang)
        ;

        $products = $query->getResult();

        return $products;
    }

    public function findExercisesReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate)
    {
        $em = $this->getEntityManager();
        $groupWhereStr = "";
        if($gaGroupID == 'all'){
        }
        if($gaGroupID == 'ungrouped'){
            $groupWhereStr = " and gs.grade IS NULL ";
        }

        if($gaGroupID != 'ungrouped' && $gaGroupID != 'all'){
            $groupWhereStr = " and gs.grade = :groupId ";
        }

        $query = null;
        if($dateRange == 'overall'){
            if($gaGroupID != 'ungrouped' && $gaGroupID != 'all'){
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') ".$groupWhereStr."
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('groupId', $gaGroupID)
                ;
            } else {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') ".$groupWhereStr."
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang)
                ;
            }
        } else {
            if($gaGroupID != 'ungrouped' && $gaGroupID != 'all') {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') and a.createdAt between :dt1 and :dt2 " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('dt1', $startDate)
                    ->setParameter('dt2', $endDate)
                    ->setParameter('groupId', $gaGroupID);
            } else {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') and a.createdAt between :dt1 and :dt2 " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('dt1', $startDate)
                    ->setParameter('dt2', $endDate);
            }
        }

        $products = $query->getResult();

        return $products;
    }

    public function findDetailedReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate)
    {
        $em = $this->getEntityManager();
        $groupWhereStr = "";
        if($gaGroupID == 'all'){
        }
        if($gaGroupID == 'ungrouped'){
            $groupWhereStr = " and gs.grade IS NULL ";
        }

        if($gaGroupID != 'ungrouped' && $gaGroupID != 'all'){
            $groupWhereStr = " and gs.grade = :groupId ";
        }

        $query = null;
        if($dateRange == 'overall'){
            if($gaGroupID != 'ungrouped' && $gaGroupID != 'all') {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname, l.id, c.mnTitle cmnTitle, c.enTitle cenTitle, l.mnTitle, l.enTitle,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total,
                (SELECT count(ex) from App:Exercise ex where ex.lesson = l.id ) progress
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname, l.id, l.mnTitle, l.enTitle
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('groupId', $gaGroupID);
            } else {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname, l.id, c.mnTitle cmnTitle, c.enTitle cenTitle, l.mnTitle, l.enTitle,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total,
                (SELECT count(ex) from App:Exercise ex where ex.lesson = l.id ) progress
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname, l.id, l.mnTitle, l.enTitle
                "
                )
                    ->setParameter('lang', $lang);
            }
        } else {
            if($gaGroupID != 'ungrouped' && $gaGroupID != 'all') {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname, l.id, c.mnTitle cmnTitle, c.enTitle cenTitle, l.mnTitle, l.enTitle,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total,
                (SELECT count(ex) from App:Exercise ex where ex.lesson = l.id ) progress
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exerciseId = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') and a.createdAt between :dt1 and :dt2 " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname, l.id, l.mnTitle, l.enTitle
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('dt1', $startDate)
                    ->setParameter('dt2', $endDate)
                    ->setParameter('groupId', $gaGroupID);
            } else {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname, l.id, c.mnTitle cmnTitle, c.enTitle cenTitle, l.mnTitle, l.enTitle,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, AVG(a.acc) accuracy,
                SUM(a.correcthit) totalTyped,
                SUM(a.mistakehit) errors,
                count(e.id) total,
                (SELECT count(ex) from App:Exercise ex where ex.lesson = l.id ) progress
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exerciseId = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') and a.createdAt between :dt1 and :dt2 " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname, l.id, l.mnTitle, l.enTitle
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('dt1', $startDate)
                    ->setParameter('dt2', $endDate);
            }
        }

        $products = $query->getResult();

        return $products;
    }

    public function findTypingtestReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate)
    {
        $em = $this->getEntityManager();
        $groupWhereStr = "";
        if($gaGroupID == 'all'){
        }
        if($gaGroupID == 'ungrouped'){
            $groupWhereStr = " and gs.grade IS NULL ";
        }

        if($gaGroupID != 'ungrouped' && $gaGroupID != 'all'){
            $groupWhereStr = " and gs.grade = :groupId ";
        }

        $query = null;
        if($dateRange == 'overall'){
            if($gaGroupID != 'ungrouped' && $gaGroupID != 'all') {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, SUM(a.mistakehit) errors, SUM(a.correcthit) totalTyped,
                count(e.id) total, AVG(a.acc) accuracy, count(e.id) cnt
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id in ('13','14') " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('groupId', $gaGroupID);
            } else {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, SUM(a.mistakehit) errors, SUM(a.correcthit) totalTyped,
                count(e.id) total, AVG(a.acc) accuracy, count(e.id) cnt
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id in ('13','14') " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang);
            }
        } else {
            if($gaGroupID != 'ungrouped' && $gaGroupID != 'all') {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, SUM(a.mistakehit) errors, SUM(a.correcthit) totalTyped,
                count(e.id) total, AVG(a.acc) accuracy, count(e.id) cnt
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id in ('13','14') and a.createdAt between :dt1 and :dt2 " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('dt1', $startDate)
                    ->setParameter('dt2', $endDate)
                    ->setParameter('groupId', $gaGroupID);
            } else {
                $query = $em->createQuery(
                    "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, SUM(a.mistakehit) errors, SUM(a.correcthit) totalTyped,
                count(e.id) total, AVG(a.acc) accuracy, count(e.id) cnt
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id in ('13','14') and a.createdAt between :dt1 and :dt2 " . $groupWhereStr . "
                group by s.id, s.username, s.firstname, s.lastname
                "
                )
                    ->setParameter('lang', $lang)
                    ->setParameter('dt1', $startDate)
                    ->setParameter('dt2', $endDate);
            }
        }

        $products = $query->getResult();

        return $products;
    }

    public function findScoreboardReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate)
    {
        $em = $this->getEntityManager();
        $groupWhereStr = "";
        if($gaGroupID == 'all'){
        }
        if($gaGroupID == 'ungrouped'){
            $groupWhereStr = " and gs.grade IS NULL ";
        }
        if($gaGroupID != 'ungrouped' && $gaGroupID != 'all'){
            $groupWhereStr = " and gs.grade = :groupId ";
        }
        $query = null;
        if($dateRange == 'overall'){
            $query = $em->createQuery(
                "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, SUM(a.mistakehit) errors, SUM(a.correcthit) totalTyped,
                count(e.id) total, AVG(a.acc) accuracy
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') ".$groupWhereStr."
                group by s.id, s.username, s.firstname, s.lastname
                "
            )
                ->setParameter('lang', $lang)
                ->setParameter('groupId', $gaGroupID)
            ;
        } else {
            $query = $em->createQuery(
                "
                SELECT s.id,s.username, s.firstname, s.lastname,
                case when g.name IS NULL then 'Ungrouped Students' else g.name end class,
                SUM(a.time) seconds, SUM(a.mistakehit) errors, SUM(a.correcthit) totalTyped,
                count(e.id) total, AVG(a.acc) accuracy
                FROM App:Statustyping a
                inner join App:Exercise e WITH a.exercise = e.id
                inner join App:Lesson l WITH e.lesson = l.id
                inner join App:Category c WITH l.cate = c.id
                inner join App:Student s WITH a.student = s.id
                inner join App:GradeStudent gs WITH s.id = gs.student
                left join App:Grade g WITH gs.grade = g.id
                where c.lang = :lang and c.id not in ('13','14') and a.createdAt between :dt1 and :dt2 ".$groupWhereStr."
                group by s.id, s.username, s.firstname, s.lastname
                "
            )
                ->setParameter('lang', $lang)
                ->setParameter('dt1', $startDate)
                ->setParameter('dt2', $endDate)
                ->setParameter('groupId', $gaGroupID)
            ;
        }

        $products = $query->getResult();

        return $products;
    }
}