<?php

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Statustyping;

class TestController extends AbstractController
{
    /**
     * @Route("{_locale}/student/test", name="tutor_test", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:Lang')->findAll();

        return $this->render('test/index.html.twig',
            array('entities' => $entities,"menu"=>3)
        );
    }

    /**
     * @Route("{_locale}/student/test/{id}", name="tutor_testlist", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function testtyping(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();

        if($this->getUser())
        {
            $user = $this->getUser();
            $user->setLastActivityAt(new \DateTime());
            $user->setLastAction('TEST');
            $em->persist($user);
            $em->flush();
        }

        $langs = $em->getRepository('App:Lang')->findOneBy(array('id' => $id));

        $testtype = null;
        $exerciseNum = 0;

        if($id == 'mongolian') {
            $testtype = $em->getRepository('App:Lesson')->findOneBy(array('id' => '36'));
            if($this->getUser()){
                $exerciseNum = $em->getRepository('App:Statustyping')->findTakenTest($user, $id, '36');
            }
            if($exerciseNum != null)
              $exerciseNum = $exerciseNum['exerciseNum'];
            else {
              $exerciseNum = 0;
            }
        }
        if($id == 'english') {
            $testtype = $em->getRepository('App:Lesson')->findOneBy(array('id' => '35'));
            if($this->getUser()) {
                $exerciseNum = $em->getRepository('App:Statustyping')->findTakenTest($user, $id, '35');
            }
            if($exerciseNum != null)
              $exerciseNum = $exerciseNum['exerciseNum'];
            else {
              $exerciseNum = 0;
            }
        }
        # хэрэглэгчийн гарын форматыг авч байгаа хэсэг
        $my_kb = $em->getRepository('App:MyKeybroard')->findOneBy(array('id' => '1'));
        if($this->getUser()) {
            $my_kb = $em->getRepository('App:MyKeybroard')->findOneBy(array('id' => $this->getUser()->getMykb()->getId()));
        }

        $testes = $em->createQueryBuilder()
            ->select("a")
            ->from('App:Exercise', 'a')
            ->where('a.lesson = :lesson')
            ->orderby('a.sortnum')
            ->setParameter('lesson', $testtype)
            ->getQuery()
            ->getResult();

        $data = array();

        //мөрүүдийг холих
        foreach ( $testes as $test ) {
            $array = explode("\\n", $test->getTutor());

            if (!is_array($array) || empty($array)) {
                return false;
            }
            $tmp = array();
            foreach ($array as $key => $value) {
                $tmp[] = array('k' => $key, 'v' => $value);
            }
            shuffle($tmp);
            $array = array();
            foreach ($tmp as $entry) {
                $array[$entry['k']] = $entry['v'];
            }

            $key = array();

            $key['lessonExerciseID'] = $test->getId().'';
            $key['lessonID'] = $testtype->getId().'';
            $key['type'] = 'mixed';
            $key['displayOrder'] = $test->getSortnum().'';
            $key['timeLimit'] = '0';
            $key['accuracyLimit'] = '30';
            $key['speedLimit'] = '0';
            $key['title'] = $test->getMnTitle();
            $key['exercise'] = implode(" \n", $array);
            $key['helpText'] = '';

            array_push($data, $key);
        }

        //$stts_count = count($em->getRepository('AppBundle:TestStatistic')->findBy(array('student' =>  $this->getUser(),'lang' => $id)));

        return $this->render('test/testtyping.html.twig', array(
            'lang' => $langs,
            'entities' => $testtype,
            'entity' => $testes,
            'json_str' => json_encode($data),
            "menu"=>3,
            'stts_count' => $exerciseNum,
            'showTestGraph' => $exerciseNum == 0 ? 0 : 1,
            'my_kb'=> $my_kb
        ));
    }

    /**
     * @Route("/{_locale}/student/stats/save/", name="test_statistics_save")
     */
    public function teststatussave(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $usr = $this->getUser();
        $success = false;
        if($usr) {
            $ping = $request->request->get('ping');
            if($ping == 1){
                $usr->setLastActivityAt(new \DateTime());
                $em->persist($usr);
                $em->flush();

                $success = true;
            } else {
                if( $usr->getId() == $request->request->get('u'))
                {

                    $usr->setLastActivityAt(new \DateTime());
                    $em->persist($usr);
                    $em->flush();

                    if($request->request->get('le') == 114409 || $request->request->get('le') == 114412)
                    {
                      $status = $em->getRepository('App:Statustyping')->findTypingTestCount($usr->getId(), $request->request->get('la'));

                      if(count($status) == 10)
                      {
                          $em->remove($status[0]);
                          $em->flush();
                      }
                    }

                    $typingstatus = new Statustyping();

                    $haveSess = $em->getRepository('App:StudentLogged')->findOneBy(array('sessId' => $request->getSession()->getId()));

                    $typingstatus->setExercise($em->getRepository('App:Exercise')->findOneBy(array('id' => $request->request->get('le')))); // Дасгалын дугаар байна.
                    $typingstatus->setStudent($usr); // бичсэн хүний дугаар байна.
                    $typingstatus->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                    $typingstatus->setModifierAt(new \DateTime(date('Y-m-d H:i:s')));
                    $typingstatus->setCorrecthit($request->request->get('t')); // бичих үсгийн тоо байна.
                    $typingstatus->setMistakehit($request->request->get('e')); // алдаатай бичсэн үсгийн тоо байна.
                    $typingstatus->setTime($request->request->get('s')); // Зарцуулсан хугацаа байна.
                    $typingstatus->setErrorKeys($request->request->get('ek'));
                    $typingstatus->setAllKeys($request->request->get('ak'));
                    $typingstatus->setAcc(100 - round(($request->request->get('e')/$request->request->get('t') )*100)); //
                    $typingstatus->setLastlogin($usr->getLastlogin());
                    $typingstatus->setKeyboard($usr->getMykb());
                    $typingstatus->setSess($haveSess);

                    $em->persist($typingstatus);
                    $em->flush();

                    $success = true;
                } else {
                    $success = false;
                }

                $success = true;
            }
        } else {
            $success = true;
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => $success ,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/{_locale}/student/stats/{lang}/", name="test_statistics")
     */
    public function teststatus(Request $request, $lang)
    {
      $usr = $this->getUser();
      if($usr) {
        return $this->render('test/teststatus.html.twig', array(
            'lang' => $lang,
        ));
      }
      else
      {
          return $this->redirect($this->generateUrl('student_login'));
      }
    }
}
