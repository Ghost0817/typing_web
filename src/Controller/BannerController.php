<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Entity\Banner;

class BannerController extends AbstractController
{
    public function abanner()
    {
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository('App:Banner')->findBannerBySysdate('A');
        $response = $this->render('banner/a_banner.html.twig', array(
            'banner' => $banner
        ));
        // set the shared max age - which also marks the response as public
        $response->setSharedMaxAge(600);

        return $response;
    }

    public function bbanner()
    {
        $response = $this->render('banner/b_banner.html.twig');
        // set the shared max age - which also marks the response as public
        $response->setSharedMaxAge(600);

        return $response;
    }

    public function cbanner()
    {
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository('App:Banner')->findBannerBySysdate('C');

        $response = $this->render('banner/c_banner.html.twig', array(
            'banner' => $banner
        ));
        // set the shared max age - which also marks the response as public
        $response->setSharedMaxAge(600);

        return $response;
    }
}
