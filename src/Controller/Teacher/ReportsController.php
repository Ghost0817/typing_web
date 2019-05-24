<?php

namespace App\Controller\Teacher;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportsController extends AbstractController
{

      /**
       * @Route("{_locale}/teacher/reports/", name="reportspage", defaults={
       *     "_locale": "mn"
       * }, requirements={
       *     "_locale": "mn|en"
       * })
       * @Security("is_granted('ROLE_TEACHER')")
       */
      public function reportsAction(Request $request)
      {

          $clss = $this->getDoctrine()
              ->getRepository('App:Grade')->findBy(array('teacher'=> $this->getUser() ));

          $lang = $this->getDoctrine()
              ->getRepository('App:Lang')
              ->findAll();

          // replace this example code with whatever you need
          return $this->render('teacher/reports.html.twig', array(
              'menu' => '4',
              'classes' => $clss,
              'langs' => $lang,
          ));
      }
}
