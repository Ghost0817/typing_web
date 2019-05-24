<?php

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatisticsController extends AbstractController
{
    /**
     * @Route("{_locale}/student/statistics/", name="tutor_statistics")
     */
    public function index(Request $request)
    {
      return $this->render('statistics/index.html.twig', array(
          "menu"     => 4,
      ));
    }
}
