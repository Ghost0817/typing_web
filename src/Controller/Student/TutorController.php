<?php

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Statustyping;
use App\Entity\Activedcourse;
use App\Entity;

class TutorController extends AbstractController
{
    /**
     * @Route("{_locale}/student/{lang}/{category}/{lesson}/", name="tutor_typing", defaults={
     *     "_locale": "mn",
     *     "num": "1"
     * }, requirements={
     *     "_locale": "mn|en",
     *     "category": "\d+",
     *     "lesson": "\d+"
     * })
     *
     * @Template()
     */
    public function typing(Request $request,$lang,$category,$lesson)
    {
        $em = $this->getDoctrine()->getManager();

        $usr = $this->getUser();

        #аль хэл дээр явч байгааг тодорхойлно.
        $langs = $em->getRepository('App:Lang')->findOneBy(array('id' => $lang));
        #Аль ангилал дээр байгааг тодорхойлно.
        $entities = $em->getRepository('App:Category')->findOneBy(array('id' => $category));
        #Аль хичээлийг хийж байгааг тодорхойлно.
        $subentities = $em->getRepository('App:Lesson')->findOneBy(array('id' => $lesson));

        if (!is_null($usr)) {
            $user = $this->getUser();
            $user->setLastActivityAt(new \DateTime());
            $user->setLastAction('LESSON,'.$lesson);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $as = $em->getRepository('App:Activedcourse')->findOneBy(array('student' => $user, 'lang' => $langs));

            if(is_null($as)){
              $as = new Activedcourse();
              $as->setLang($langs);
              $as->setCategory($entities);
              $as->setLesson($subentities);
              $as->setStudent($usr);

              $em->persist($as);
              $em->flush();
            } else {
              $as->setLang($langs);
              $as->setCategory($entities);
              $as->setLesson($subentities);

              $em->persist($as);
              $em->flush();
            }
        }

        $exercises = $em->createQueryBuilder()
            ->select("a")
            ->from('App:Exercise', 'a')
            ->where('a.lesson = :lesson')
            ->orderby('a.sortnum')
            ->setParameter('lesson', $subentities)
            ->getQuery()
            ->getResult();

        $data = array();

        //мөрүүдийг холих
        $o = 0;
        foreach ( $exercises as $item ) {
            $key = array();

            $key['lessonExerciseID'] = $item->getId().'';
            $key['lessonID'] = $subentities->getId().'';
            $key['type'] = 'mixed';
            $key['displayOrder'] = $item->getSortnum().'';
            $key['timeLimit'] = '0';
            $key['accuracyLimit'] = '30';
            $key['speedLimit'] = '0';
            if((!$usr || $usr->getRole() == "ROLE_FREE") && $subentities->getIsPremium() == 1 && $o == 2)
            {
              $key['title'] = "restricted";
              $key['exercise'] = "restricted";
            } else {
              $key['title'] = $item->getMnTitle();
              $key['exercise'] = str_replace(" \\n","\n",$item->getTutor());
              $o ++;
            }
            $key['helpText'] = '';

            array_push($data, $key);
        }

        $exerciseNum = 0;
        $my_kb = $em->getRepository('App:MyKeybroard')->findOneBy(array('id' => 1));

        if ( !is_null($usr) ) {
            # хэрэглэгчийн гарын форматыг авч байгаа хэсэг
            $my_kb = $em->getRepository('App:MyKeybroard')->findOneBy(array('id' => $usr->getMykb()->getId()));

            $exerciseNum = $em->getRepository('App:Statustyping')->findCurrentExe($usr->getId(), $lang, $lesson);
            if($exerciseNum != null){
              //dump($exerciseNum);die();
              $exerciseNum = $exerciseNum['exerciseNum'];
            } else {
              $exerciseNum = 0;
            }
            if(count($data) == $exerciseNum) {
              return $this->redirect(
                  $this->generateUrl(
                      'tutor_course',
                      array('lang' => $lang)
                  )
              );
            }
        }

        return $this->render('tutor/typing.html.twig',
            array(
                'lang' => $langs,
                'entities' => $entities,
                'subentities' => $subentities,
                'json_str' => json_encode($data),
                'exerciseNum' => $exerciseNum,
                "menu" => 1,
                'my_kb' => $my_kb
            )
        );
    }

    /**
     * @Route("{_locale}/student/restart/{lang}/{category}/{lesson}", name="tutor_retyping", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en",
     *     "category": "\d+",
     *     "lesson": "\d+"
     * })
     *
     * @Template()
     */
    public function retyping(Request $request,$lang,$category,$lesson)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->getUser();
        if (!is_null($usr)) {
            #Хэрэглэгч байгаа бол ...
            #Тухайн хичээлийн дасгалуудыг авч байна.
            $dels = $em->getRepository('App:Exercise')->findBy(array('lesson'=>$lesson));

            $status = $em->getRepository('App:Statistic')->findOneBy(array('lesson'=>$lesson,'student' => $usr));
            #Энэ нь бичигдээгүй байгаа дасгалуудыг авч байгаа юм
            $arrayOfAlrealdyUsedIds = array();
            $x = 0;
            foreach ( $dels as $subitem ) {
                $arrayOfAlreadyUsedIds[$x] = $subitem->getId();
                $x++;
            }

            if(count($dels) != 0)
            {
                $query = $em->createQueryBuilder()
                    ->select("a")
                    ->from('App:Statustyping', 'a')
                    ->where('a.exerciseId in ('.implode(',', $arrayOfAlreadyUsedIds).')')
                    ->getQuery()
                    ->getResult();

                foreach ( $query as &$item ) {
                    $em->remove($item);
                    $em->flush();
                }
                $em->remove($status);
                $em->flush();
                #Энд хэрвээ статус нь дүүрээд дасгал нь дууссан бол буцааж тухайн хэлний курсрүү
                return $this->redirect(
                    $this->generateUrl(
                        'tutor_typing',
                        array('lang' => $lang,
                            'category' => $category,
                            'lesson' => $lesson,
                            'num' => 1
                        )
                    )
                );
            }
        }

        #Энд хэрвээ статус нь дүүрээд дасгал нь дууссан бол буцааж тухайн хэлний курсрүү
        return $this->redirect(
            $this->generateUrl(
                'tutor_course',
                array('lang' => $lang)
            )
        );
    }
}
