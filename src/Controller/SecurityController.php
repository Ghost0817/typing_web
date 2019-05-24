<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("{_locale}/student/login", name="student_login")
     */
    public function student_login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/student_login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'menu' => 'login']);
    }

    /**
     * @Route("{_locale}/student/login_check", name="student_login_check")
     */
    public function student_login_check(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/student_login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'menu' => 'login']);
    }

    /**
     * @Route("/student/logout", name="student_logout")
     */
    public function student_logout(AuthenticationUtils $authenticationUtils): Response
    {

    }
    /**
     * @Route("{_locale}/teacher/login", name="teacher_login", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function teacher_login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/teacher_login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'menu'          => 'login',
        ));
    }

    /**
     * @Route("{_locale}/teacher/login_check", name="teacher_login_check", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function teacher_login_check(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/teacher_login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'menu'          => 'login',
        ));
    }

    /**
     * @Route("{_locale}/teacher/logout", name="teacher_logout")
     */
    public function teacher_logout(AuthenticationUtils $authenticationUtils): Response
    {

    }
}
