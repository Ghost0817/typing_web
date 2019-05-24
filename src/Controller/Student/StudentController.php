<?php

namespace App\Controller\Student;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Form\Model\StudentChangeProfile;
use App\Form\Model\StudentChangeKeyboard;
use App\Form\Model\StudentChangeOptions;
use App\Form\Model\ChangePassword;
use App\Form\Model\ForgotPassword;
use App\Form\Model\PasswordResetModel;

use App\Entity\Student;
use App\Entity\PasswordReset;

use App\Form\StudentProfileChange;
use App\Form\StudentOptionsChange;
use App\Form\StudentKeyboardChange;
use App\Form\PasswordResetType;
use App\Form\PasswordChange;
use App\Form\StudentType;
use App\Form\ForgotType;

class StudentController extends AbstractController
{

    /**
     * @Route("{_locale}/student/password-reset/", name="student_forgot", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * 
     */
    public function forgot(Request $request, TranslatorInterface $translator, \Swift_Mailer $mailer)
    {
        $registration = new ForgotPassword();

        $form   = $this->createForm(ForgotType::class, $registration);

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $registration = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('App:Student')->findOneBy(array('email' => $registration->getEmail() ));

                if (!$entity) {

                    $this->addFlash(
                        'error',
                        $translator->trans('Can\'t find that email, sorry.')
                    );

                    return $this->render('student/forgot.html.twig', array(
                        'entity' => $entity,
                        'form' => $form->createView(),
                        'menu' => 'forgot'
                    ));
                }
                else
                {

                    $passwordreset = new PasswordReset();

                    $passwordreset->setStudent($entity);
                    $passwordreset->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                    $passwordreset->setActivationkey($passwordreset->getActivationkey());

                    $em->persist($passwordreset);
                    $em->flush();

                    $message = (new \Swift_Message('[Bicheech] '. $translator->trans('Please reset your password')))// we create a new instance of the Swift_Message class
                    ->setFrom('info@bicheech.com')// we configure the sender
                    ->setTo($entity->getEmail())// we configure the recipient
                    ->setBody($this->renderView('tempmail/passwordreset.txt.twig',
                        ['token' => $passwordreset->getActivationkey(),]
                    ), 'text/html')// and we pass the $name variable to the text template which serves as a body of the message
                    ;
                    $mailer->send($message);     // then we send the message.

                    return $this->render('student/confirmationsent.html.twig', array(
                        'entity' => $entity,
                        'form' => $form->createView(),
                        'menu' => 'forgot'
                    ));
                }
            }
        }

        return $this->render('student/forgot.html.twig', array(
            'form'   => $form->createView(),
            'menu' => 'forgot'
        ));
    }

    /**
     * @Route("{_locale}/student/password-reset/{active}", name="pass_reset", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * 
     */
    public function passreset(Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer,$active)
    {
        $entity = new PasswordResetModel();
        $form = $this->createForm(PasswordResetType::class, $entity);


        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);
        $token = $em->getRepository('App:PasswordReset')->findOneBy(array('activationkey' => $active ));

        if($token) {
            $datetime1 = $token->getCreatedAt();
            $datetime2 = new \DateTime(date('Y-m-d H:i:s'));
            $interval = date_diff($datetime1, $datetime2);
            if($interval->format('%a') == '0')
            {

                $entity = $em->getRepository('App:Student')->findOneBy(array('id' => $token->getStudent()->getId() ));
                if ($request->isMethod('POST')) {
                    if ($form->isValid()) {

                        $registration = $form->getData();
                        $entities = $em->getRepository('App:PasswordReset')->findBy(array('student' => $entity ));

                        foreach ($entities as $rear) {
                            $ch_dt = new \DateTime(date('Y-m-d H:i:s'));
                            $rear->setCreatedAt($ch_dt->sub(new \DateInterval('P2D')));

                            $em->persist($rear);
                            $em->flush();
                        }

                        //start Dynamically Encoding a Password
                        $password = $passwordEncoder->encodePassword($entity, $registration->getPassword());
                        $entity->setPassword($password);
                        // end Dynamically Encoding a Password
                        $em->persist($entity);
                        $em->flush();

                        $this->addFlash(
                            'success',
                            $translator->trans('Save Successful').'.'
                        );

                        $message = (new \Swift_Message('[Bicheech] '. $translator->trans('Your password has changed')))// we create a new instance of the Swift_Message class
                        ->setFrom('info@bicheech.mn')// we configure the sender
                        ->setTo($entity->getEmail())// we configure the recipient
                        ->setBody($this->renderView('tempmail/passwordhaschanged.txt.twig',
                            array(
                                    'name' => $entity->getUsername(),
                            )
                        ), 'text/html')// and we pass the $name variable to the text template which serves as a body of the message
                        ;
                        $mailer->send($message);     // then we send the message.

                        return $this->redirect($this->generateUrl('student_login'));
                    }
                }
            }
            else
            {
                $this->addFlash(
                    'error',
                    $translator->trans('It looks like you clicked on an invalid password reset link. Please try again.')
                );
                return $this->redirect($this->generateUrl('tutor_forgot'));
            }

        }
        else
        {
            $this->addFlash(
                'error',
                $translator->trans('It looks like you clicked on an invalid password reset link. Please try again.')
            );
            return $this->redirect($this->generateUrl('tutor_forgot'));
        }

        return $this->render('student/passreset.html.twig', array(
          'menu' => 'passreset',
          'form'   => $form->createView(),
          'active' => $active,
          'user' => $entity
        ));

    }

    /**
     * @Route("{_locale}/student/signup/", name="tutor_new_register", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * 
     */
    public function register(Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Student();
        $form   = $this->createForm(StudentType::class, $entity);

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $entity->setActivationkey($entity->getActivationkey());

                $keybr = $em->getRepository('App:MyKeybroard')->findOneById(1);

                //set defualt values;
                $entity->setSalt($entity->getSalt());
                $entity->setGender('m');
                $entity->setEnableSounds(false);
                $entity->setMeasureSpeed('wpm');
                $entity->setMykb($keybr);
                $entity->setFirstname('');
                $entity->setLastname('');
                $entity->setRole('ROLE_FREE');
                $entity->setIsActive(1);
                $entity->setLastlogin(new \DateTime("1970-07-08 11:00:00.00"));
                $entity->setCreated(new \DateTime(date('Y-m-d H:i:s')));
                $entity->setUpdated(new \DateTime(date('Y-m-d H:i:s')));

                // strat Dynamically Encoding a Password
                $encoder = $passwordEncoder->encodePassword($entity, $entity->getPassword());
                $entity->setPassword($encoder);
                //end Dynamically Encoding a Password

                $em->persist($entity);
                $em->flush();

                $this->addFlash(
                        'success',
                    $translator->trans('Save Successful').'.'
                    );

                $message = (new \Swift_Message($translator->trans('Registration successful')))// we create a new instance of the Swift_Message class
                ->setFrom('info@bicheech.mn')// we configure the sender
                ->setTo($entity->getEmail())// we configure the recipient
                ->setBody($this->renderView('tempmail/mail.html.twig',
                    array(
                        'name' => $entity->getUsername(),
                        'key' => $entity->getActivationkey()
                    )
                ), 'text/html')// and we pass the $name variable to the text template which serves as a body of the message
                ;
                $mailer->send($message);     // then we send the message.

                return $this->redirect($this->generateUrl('tutor_new_register'));
            }
        }

        return $this->render('student/register.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'menu'   => 'register'
        ));
    }

    /**
     * @Route("{_locale}/student/preferences/", name="tutor_user_profile_change", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * 
     */
    public function useredite(Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();

        if( $this->getUser() ) {
            $entity = $this->getUser();

            $pass_entity = new ChangePassword();
            $pass_form = $this->createForm(PasswordChange::class, $pass_entity);

            $p_change = new StudentChangeProfile();
            $o_change = new StudentChangeOptions();
            $kb_change = new StudentChangeKeyboard();

            $p_change->setUsername($entity->getUsername());
            $p_change->setEmail($entity->getEmail());
            $p_change->setFirstname($entity->getFirstname());
            $p_change->setLastname($entity->getLastname());
            $p_change->setGender($entity->getGender());

            $o_change->setMeasureSpeed($entity->getMeasureSpeed());
            $o_change->setEnableSounds($entity->getEnableSounds());

            $kb_change->setMykb($entity->getMykb());

            $form = $this->createForm(StudentProfileChange::class, $p_change);
            $o_form = $this->createForm(StudentOptionsChange::class, $o_change);
            $kb_form = $this->createForm(StudentKeyboardChange::class, $kb_change);

            if ($request->isMethod('POST')) {
              if ($request->request->has('student_profile_change')) {
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $p_change = $form->getData();

                    $entity->setUsername($p_change->getUsername());
                    $entity->setEmail($p_change->getEmail());
                    $entity->setFirstname($p_change->getFirstname());
                    $entity->setLastname($p_change->getLastname());
                    $entity->setGender($p_change->getGender());

                    $em->persist($entity);
                    $em->flush();
                    $this->addFlash(
                        'success',
                        $translator->trans('Save Successful').'.'
                    );
                    return $this->redirect($this->generateUrl('tutor_user_profile_change'));
                }
              }
              if ($request->request->has('student_options_change')) {
                $o_form->handleRequest($request);
                if ($o_form->isSubmitted() && $o_form->isValid()) {
                    $o_change = $o_form->getData();
                    $entity->setMeasureSpeed($o_change->getMeasureSpeed());
                    $entity->setEnableSounds($o_change->getEnableSounds());

                    $em->persist($entity);
                    $em->flush();
                    $this->addFlash(
                        'o_success',
                        $translator->trans('Save Successful').'.'
                    );
                    return $this->redirect($this->generateUrl('tutor_user_profile_change'));
                }
              }
              if ($request->request->has('student_keyboard_change')) {
                $kb_form->handleRequest($request);
                if ($kb_form->isSubmitted() && $kb_form->isValid()) {
                    $kb_change = $kb_form->getData();
                    $entity->setMykb($kb_change->getMykb());

                    $em->persist($entity);
                    $em->flush();
                    $this->addFlash(
                        'kb_success',
                        $translator->trans('Save Successful').'.'
                    );
                    return $this->redirect($this->generateUrl('tutor_user_profile_change'));
                }
              }
              if ($request->request->has('password_change')) {
                $pass_form->handleRequest($request);
                if ($pass_form->isSubmitted() && $pass_form->isValid()) {

                    $encoder = $passwordEncoder->encodePassword($entity, $pass_entity->getPlainPassword());
                    $entity->setPassword($encoder);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $this->addFlash(
                        'pass_success',
                        $translator->trans('Password change successfully').'!'
                    );

                    return $this->redirectToRoute('tutor_user_profile_change');
                }
              }
            }

            return $this->render('student/profilechange.html.twig', array(
                'menu' => 0,
                'entity' => $entity,
                'form' => $form->createView(),
                'o_form' => $o_form->createView(),
                'kb_form' => $kb_form->createView(),
                'pass_form' => $pass_form->createView(),
            ));
        } else {
            return $this->redirect($this->generateUrl('tutor_index'));
        }
    }

    /**
     * @Route("/student/lesson/sound/", name="tutor_sound")
     * 
     */
    public function sound(Request $request)
    {
      if( $this->getUser() ) {
        $sounds = $request->request->get('sounds');
        if(isset($sounds)) {
          $entity = $this->getUser();
          $entity->setEnableSounds($sounds);
          $em = $this->getDoctrine()->getManager();
          $em->persist($entity);
          $em->flush();
        }
      }
      return new Response();
    }
}
