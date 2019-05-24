<?php

namespace App\Controller\Teacher;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Entity\Grade;


class ClassesController extends AbstractController
{

      /**
       * @Route("{_locale}/teacher/classes/", name="classespage", defaults={
       *     "_locale": "mn"
       * }, requirements={
       *     "_locale": "mn|en"
       * })
       * @Security("is_granted('ROLE_TEACHER')")
       */
      public function classes(Request $request, $_locale)
      {
          if($_locale === "mn"){
              $TXT_UNGROUPED_STUDENTS = 'Ангилаагүй Сурагчид';
          } else {
              $TXT_UNGROUPED_STUDENTS = 'Ungrouped Students';
          }
          $grouped = $this->getDoctrine()
              ->getRepository('App:GradeStudent')
              ->findClassStudent($this->getUser());

          $ungrouped = $this->getDoctrine()
              ->getRepository('App:GradeStudent')
              ->findUngroupedStudent($this->getUser(), $TXT_UNGROUPED_STUDENTS);

          $data =array();

          foreach ($ungrouped as $key) {
              array_push($data, $key);
          }

          foreach ($grouped as $key) {
              array_push($data, $key);
          }

          // replace this example code with whatever you need
          return $this->render('teacher/classes.html.twig', array(
              'menu' => '2',
              'classes' => json_encode($data),
          ));
      }

      /**
       * @Route("{_locale}/teacher/classes/save/id/{id}", name="classsavepage", methods={"POST"}, defaults={
       *     "_locale": "mn"
       * }, requirements={
       *     "_locale": "mn|en"
       * })
       * @Security("is_granted('ROLE_TEACHER')")
       */
      public function classsave(Request $request, $_locale, $id)
      {
          $em = $this->getDoctrine()->getManager();
          if(isset($id) && $id != '0'){
              $edit_class = $this->getDoctrine()
                  ->getRepository('App:Grade')
                  ->findOneById($id);

              $edit_class->setName($request->request->get('name'));

              $em->persist($edit_class);
              $em->flush();
              $msg = '';
              if($_locale === 'mn') {
                  $msg = '<strong>Анги Шинэчлэгдсэн!</strong>';
              } else {
                  $msg = '<strong>Class Updated!</strong>';
              }


              $this->addFlash(
                  'success',
                  $msg
              );
          } else {
              $new_class = new Grade();

              $new_class->setName($request->request->get('name'));
              $new_class->setTeacher($this->getUser());
              $new_class->setCreated(new \DateTime(date('Y-m-d H:i:s')));

              $em->persist($new_class);
              $em->flush();

              $url = $this->generateUrl(
                  'usercreatepage'
              );
              $msg = '';
              if($_locale === 'mn') {
                  $msg = '<strong>Анги үүсгэсэн!</strong> Таны шинэ анги амжилттай үүсгэсэн. Одоо <a href="' . $url . '">сурагч нэмэх / үүсгэх</a> үү?';
              } else {
                  $msg = '<strong>Class Created!</strong> Your new class was created successfully. Would you like to <a href="' . $url . '">create / add student</a> now?';
              }

              $this->addFlash(
                  'success',
                  $msg
              );
          }

          $response = new Response();
          $response->setContent(json_encode(array(
              'success' => true,
              'gid'     => $id,
          )));
          $response->headers->set('Content-Type', 'application/json');
          return $response;
      }

      /**
       * @Route("{_locale}/teacher/classes/delete/{id}", name="classdeletepage", methods={"GET"}, defaults={
       *     "_locale": "mn"
       * }, requirements={
       *     "_locale": "mn|en"
       * })
       * @Security("is_granted('ROLE_TEACHER')")
       */
      public function classdelete(Request $request, $_locale, $id)
      {
          $em = $this->getDoctrine()->getManager();

          $del_class = $this->getDoctrine()
              ->getRepository('App:Grade')
              ->findOneById($id);

          $getnull_grade = $this->getDoctrine()
              ->getRepository('App:GradeStudent')
              ->findBy(array('grade' => $del_class ));

          foreach ($getnull_grade as $key) {
            $key->setGrade(null);
            $em->persist($key);
            $em->flush();
          }

          $em->remove($del_class);
          $em->flush();

          $msg = '';
          if($_locale === 'mn') {
              $msg = '<strong>Устгал амжилттай!</strong> Энэ ангилал дахь бүх сурагчид \'Ангилаагүй Сурагчид\' анги руу орсон болно.';
          } else {
              $msg = '<strong>Delete successful!</strong> All students in that class was moved into the \'Ungrouped Students\' class.';
          }

          $this->addFlash(
              'success',
              $msg
          );

          $response = new Response();
          $response->setContent(json_encode(array(
              'success' => true
          )));
          $response->headers->set('Content-Type', 'application/json');
          return $response;
      }

}
