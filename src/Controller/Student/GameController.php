<?php

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Method;

class GameController extends AbstractController
{
    /**
     * @Route("{_locale}/student/games/", name="tutor_games", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function games()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:Game')->findAll();
        //dump($entities);die();
        if($this->getUser())
        {
            $user = $this->getUser();
            $user->setLastActivityAt(new \DateTime());
            $user->setLastAction('GAMES');

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('game/index.html.twig',
            array('entities' => $entities,"menu"=>2)
        );
    }
    
    /**
      * @Route("{_locale}/student/games/play/game/{id}", name="tutor_gamesinfo", defaults={
      *     "_locale": "mn"
      * }, requirements={
      *     "_locale": "mn|en",
      *     "id": "\d+"
      * })
      */
    public function gameinfo($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App:Game')->findOneBy(array('id' => $id));

        if($this->getUser())
        {
            $user = $this->getUser();
            $user->setLastActivityAt(new \DateTime());
            $user->setLastAction("PLAYGAME,". $entity->getId() ."");

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('game/show.html.twig',
            array('entity' => $entity,"menu"=>2)
        );
    }
}
