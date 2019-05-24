<?php

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Activedcourse;
use App\Entity\StudentLogged;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="_welcome", defaults={
     *     "_locale": "mn"
     * })
     * @Route("{_locale}/student/start", name="tutor_index", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function index(Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $em = $this->getDoctrine()->getManager();

        if($this->getUser() && false === $authChecker->isGranted('ROLE_TEACHER'))
        {
            $user = $this->getUser();
            $user->setLastActivityAt(new \DateTime('now'));
            $user->setLastAction('COURSE');

            $em->persist($user);
            $em->flush();

            if ($request->hasSession() && ($session = $request->getSession())) {


                $hasSess = $em->getRepository('App:StudentLogged')->findOneBy(array('sessId' => $request->getSession()->getId()));
                if(!$hasSess){
                    $SL = new StudentLogged();
                    $SL->setSessId($request->getSession()->getId());
                    $SL->setStudent($user);
                    $SL->setSessDate(new \DateTime('now'));

                    $em->persist($SL);
                    $em->flush();
                }
                //StudentLogged->set
            }
        }
        $entities = $em->getRepository('App:Lang')->findAll();

        return $this->render('default/index.html.twig', [
            'entities' => $entities,
            "menu"     => 1,
        ]);
    }

    /**
     * @Route("{_locale}/student/lesson/{lang}/", name="tutor_course", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function course($lang)
    {
        $em = $this->getDoctrine()->getManager();

        $usr = $this->getUser();

        $langs = $em->getRepository('App:Lang')->findOneBy(array('id' => $lang));

        $entities = $em->getRepository('App:Category')->findViewCategory($lang, $usr);

        $sbentities = null;

        $as = null;

        if (!is_null($usr)) {
            $user = $this->getUser();
            $user->setLastActivityAt(new \DateTime());
            $user->setLastAction('COURSE');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $as = $em->getRepository('App:Activedcourse')->findOneBy(array('student' => $user, 'lang' => $langs));

            $sbentities = $em
              ->getRepository('App:Lesson')
              ->findViewLessonListWithStatus($usr->getId());
            $data = array();
            foreach ($sbentities as $key) {
              if( $key['alltyped'] != null && $key['seconds'] != null && $key['errors'] != null ) {
                $key['netSpeed'] = $this->calculateSpeed($usr->getMeasureSpeed(), $key['alltyped'], $key['seconds'], $key['errors']);
              }
              array_push( $data, $key);
            }
            $sbentities = $data;
        }
        else{
          $sbentities = $em
            ->getRepository('App:Lesson')
            ->findViewLessonList();
        }

        return $this->render('default/course.html.twig',
            array(
                'lang' => $lang,
                'entities' => $entities,
                'subentities' => $sbentities,
                'activedc' => $as,
                'menu'=>1)
        );
    }

    /**
     * @Route("{_locale}/student/lesson/{lang}/open/{slug}", name="tutor_course_to", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function courseto( $lang, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $usr = $this->getUser();

        $entities = $em->getRepository('App:Category')->findViewCategory($lang, $usr);

        $sbentities = null;

        if (!is_null($usr)) {
            $user = $this->getUser();
            $user->setLastActivityAt(new \DateTime());
            $user->setLastAction('COURSE');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $sbentities = $em
              ->getRepository('App:Lesson')
              ->findViewLessonListWithStatus($usr->getId());

            $as = $em->getRepository('App:Activedcourse')->findOneBy(array('student' => $user, 'lang' => $lang));

            $data = array();
            foreach ($sbentities as $key) {
              if( $key['alltyped'] != null && $key['seconds'] != null && $key['errors'] != null ) {
                $key['netSpeed'] = $this->calculateSpeed($usr->getMeasureSpeed(), $key['alltyped'], $key['seconds'], $key['errors']);
              }
              array_push( $data, $key);
            }
            $sbentities = $data;
        }
        else {
          $sbentities = $em
            ->getRepository('App:Lesson')
            ->findViewLessonList();
        }

        return $this->render('default/courseto.html.twig',
            array(
                'lang' => $lang,
                'entities' => $entities,
                'subentities'=>$sbentities,
                'activedc' => $as,
                'slug'=>$slug,
                'menu'=>1)
        );
    }

    public function calculateSpeed($type, $characters, $seconds, $errors){
      $words = 0;
      $minutes = 0;
      $speed = null;

      if($type == "kph"){
        $speed = round($characters/$seconds*3600);
      } elseif ($type == "wpm") {
        $words = ($characters - ($errors * 5))/5;		// begin WPM calculation
        $minutes = $seconds/60;
        $speed = max(round($words/$minutes),0);
      } else {
        $words = ($characters - ($errors * 5));		// begin WPM calculation
  			$minutes = $seconds/60;
  			$speed = max(round($words/$minutes),0);
      }
      return ($speed == null)?100:$speed;
    }
}
