<?php

namespace App\Controller\Teacher;

use App\Entity\Teacher;
use App\Entity\PasswordReset;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Form\Model\ChangePassword;
use App\Form\Model\ChangeProfile;
use App\Form\Model\ChangeSettings;
use App\Form\Model\ForgotPassword;
use App\Form\Model\PasswordResetModel;

use App\Form\PasswordChange;
use App\Form\ProfileChange;
use App\Form\SettingsChange;
use App\Form\TeacherType;
use App\Form\ForgotType;
use App\Form\PasswordResetType;


class TeacherController extends AbstractController
{

    /**
     * @Route("{_locale}/teacher/signup", name="teacher_register", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     *
     */
    public function signup(Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, $_locale)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Teacher();
        $form   = $this->createForm(TeacherType::class, $entity);

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $entity->setActivationkey($entity->getActivationkey());
                $entity->setIsActive(1);
                $entity->setLastlogin(new \DateTime("1970-07-08 11:00:00.00"));
                $entity->setCreated(new \DateTime(date('Y-m-d H:i:s')));
                $entity->setUpdated(new \DateTime(date('Y-m-d H:i:s')));
                $entity->setLanguage($_locale);
                $entity->setScoreboard(0);

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
                ->setBody($this->renderView('tempmail/mailforteacher.html.twig',
                    array(
                        'name' => $entity->getUsername(),
                        'key' => $entity->getActivationkey()
                    )
                ), 'text/html')// and we pass the $name variable to the text template which serves as a body of the message
                ;
                $mailer->send($message);     // then we send the message.

                return $this->redirect($this->generateUrl('teacher_register'));
            }
        }

        // replace this example code with whatever you need
        return $this->render('teacher/signup.html.twig', array(
            'menu'   => 'register',
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("{_locale}/teacher/active", name="teacher_active", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     *
     */
    public function activeuser(Request $request, $_locale, $_keygen)
    {

        //http://localhost:8000/mn/teacher/active/
        // replace this example code with whatever you need
        return $this->render('teacher/active.html.twig', array(
            'menu'   => 'active',
        ));
    }

    /**
     * @Route("{_locale}/teacher/password-reset", name="teacher_forgot", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     *
     */
    public function forgot(Request $request,TranslatorInterface $translator, \Swift_Mailer $mailer)
    {
        $registration = new ForgotPassword();

        $form   = $this->createForm(ForgotType::class, $registration);

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isValid()) {
                $registration = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('App:Teacher')->findOneBy(array('email' => $registration->getEmail() ));

                if (!$entity) {

                    $this->addFlash(
                        'error',
                        $translator->trans('Can\'t find that email, sorry.')
                    );

                    return $this->render('teacher/forgot.html.twig', array(
                        'entity' => $entity,
                        'form' => $form->createView(),
                        'menu' => 'forgot'
                    ));
                }
                else
                {
                    $passwordreset = new PasswordReset();

                    $passwordreset->setTeacher($entity);
                    $passwordreset->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
                    $passwordreset->setActivationkey($passwordreset->getActivationkey());

                    $em->persist($passwordreset);
                    $em->flush();

                    $message = (new \Swift_Message('[Bicheech] '. $translator->trans('Please reset your password')))// we create a new instance of the Swift_Message class
                    ->setFrom('info@bicheech.com')// we configure the sender
                    ->setTo($entity->getEmail())// we configure the recipient
                    ->setBody($this->renderView('tempmail/passwordresetteacher.txt.twig',
                        [
                            'token' => $passwordreset->getActivationkey(),
                        ]
                    ), 'text/html')// and we pass the $name variable to the text template which serves as a body of the message
                    ;
                    $mailer->send($message);     // then we send the message.

                    return $this->render('teacher/confirmationsent.html.twig', array(
                        'entity' => $entity,
                        'form' => $form->createView(),
                        'menu' => 'forgot'
                    ));
                }
            }
        }

        return $this->render('teacher/forgot.html.twig', array(
            'form'   => $form->createView(),
            'menu' => 'forgot'
        ));
    }

    /**
     * @Route("{_locale}/teacher/password-reset/{active}", name="teacher_pass_reset", defaults={
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
                $entity = $em->getRepository('App:Teacher')->findOneBy(array('id' => $token->getTeacher()->getId() ));
                if ($request->isMethod('POST')) {
                    if ($form->isValid()) {

                        $registration = $form->getData();
                        $entities = $em->getRepository('App:PasswordReset')->findOneBy(array('teacher' => $entity, 'activationkey' => $active ));

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
            return $this->redirect($this->generateUrl('teacher_forgot'));
        }

        return $this->render('student/passreset.html.twig', array(
            'menu' => 'passreset',
            'form'   => $form->createView(),
            'active' => $active,
            'user' => $entity
        ));

    }


    /**
     * @Route("{_locale}/teacher/profile", name="accountpage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function account(Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder)
    {

        $entity = $this->getUser();

        $pass_entity = new ChangePassword();
        $pass_form = $this->createForm(PasswordChange::class, $pass_entity);


        $pro_entity = new ChangeProfile();

        $pro_entity->setUsername($entity->getUsername());
        $pro_entity->setEmail($entity->getEmail());
        $pro_entity->setLastname($entity->getLastname());
        $pro_entity->setLanguage($entity->getLanguage());
        $pro_entity->setCountry($entity->getCountry());
        $pro_entity->setState($entity->getState());
        $pro_entity->setCity($entity->getCity());

        $pro_form = $this->createForm(ProfileChange::class, $pro_entity);


        $sett_entity = new ChangeSettings();

        $sett_entity->setScoreboard($entity->getScoreboard());

        $sett_form = $this->createForm(SettingsChange::class, $sett_entity);

        if('POST' === $request->getMethod()) {

            if ($request->request->has('profile_change')) {
                $pro_form->handleRequest($request);
                if ($pro_form->isSubmitted() && $pro_form->isValid()) {

                    $entity->setUsername($pro_entity->getUsername());
                    $entity->setEmail($pro_entity->getEmail());
                    $entity->setLastname($pro_entity->getLastname());
                    $entity->setLanguage($pro_entity->getLanguage());
                    $entity->setCountry($pro_entity->getCountry());
                    $entity->setState($pro_entity->getState());
                    $entity->setCity($pro_entity->getCity());

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $this->addFlash(
                        'pro_success',
                        $translator->trans('Account information saved successfully').'.'
                    );

                    return $this->redirectToRoute('accountpage');
                }
                //var_dump('password_change');die();
            }

            if ($request->request->has('password_change')) {
                $pass_form->handleRequest($request);
                if ($pass_form->isSubmitted() && $pass_form->isValid()) {

                    $encoded = $passwordEncoder->encodePassword($entity, $pass_entity->getPlainPassword());
                    $entity->setPassword($encoded);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $this->addFlash(
                        'pass_success',
                        $translator->trans('Password change successfully').'!'
                    );

                    return $this->redirectToRoute('accountpage');
                }
            }

            if ($request->request->has('settings_change')) {
                $sett_form->handleRequest($request);
                if ($sett_form->isSubmitted() && $sett_form->isValid()) {

                    $entity->setScoreboard($sett_entity->getScoreboard());

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $this->addFlash(
                        'settings_success',
                        $translator->trans('Settings saved successfully').'.'
                    );

                    return $this->redirectToRoute('accountpage');
                }
            }
        }

        // replace this example code with whatever you need
        return $this->render('teacher/account.html.twig', array(
            'menu' => '',
            'pass_form' => $pass_form->createView(),
            'pro_form' => $pro_form->createView(),
            'sett_form' => $sett_form->createView(),
        ));
    }

    /**
     * @Route("{_locale}/teacher/main", name="teacherpage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function index(Request $request)
    {

        $clss = $this->getDoctrine()
            ->getRepository('App:Grade')->findBy(array('teacher'=> $this->getUser() ));

        $students = $this->getDoctrine()
            ->getRepository('App:GradeStudent')
            ->findStudentClass($this->getUser());

        $data = array();

        foreach ($students as $key) {
          $key['userID'] = $key['userID'].'';
          $key['email'] = $key['email'] == null? '': $key['email'];
          $key['lastLogin'] = $key['lastLogin']->format('Y-m-d H:i:s');
          $key['signupDate'] = $key['signupDate']->format('Y-m-d H:i:s');

          $data[ $key['userID'] ] = $key;
        }

        // replace this example code with whatever you need
        return $this->render('teacher/index.html.twig', array(
            'menu' => '1',
            'students' => json_encode($data),
            'classes' => $clss,
        ));
    }

    /**
     * @Route("{_locale}/teacher/lessons/", name="lessonspage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function lessons(Request $request, $_locale)
    {
        $lssn = $this->getDoctrine()
            ->getRepository('App:Lesson')
            ->findViewLessonInfo('english');


        $lssn1 = $this->getDoctrine()
            ->getRepository('App:Lesson')
            ->findViewLessonInfo('mongolian');

        $data =array();

        foreach ($lssn as $key) {
          $course = $_locale == 'en'? $key['cenTitle']:$key['cmnTitle'];
          $name = $_locale == 'en'? $key['enTitle']:$key['mnTitle'];
          $row['lessonID'] = $key['lessonID'].'';
          $row['course'] = $key['sortnum'] . '_' . $course;
          $row['name'] = $name . ( $key['isPremium'] == 1 ? ' (<strong style=\"font-weight: bold\">Premium Accounts Only</strong>)' : '' );
          $row['active'] = $key['active'] == '1' ? true : false;
          $row['disabled'] = $key['disabled'] == '1' ? true : false;
          $row['displayOrder'] = $key['displayOrder'] == '' ? null : $key['displayOrder'];

          array_push($data, $row);
        }

        // replace this example code with whatever you need
        return $this->render('teacher/lessons.html.twig', array(
            'menu' => '5',
            'lessonData' => json_encode($data),
        ));
    }

    /**
     * @Route("{_locale}/teacher/lessons/view/id/{id}/languageID/{lang}", name="lessonsviewpage", defaults={"lang" = "","_locale": "mn"}, requirements={"_locale": "mn|en"})
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function lessonsview(Request $request, $id, $lang)
    {
        $lssn = $this->getDoctrine()
            ->getRepository('App:Lesson')->findOneById($id);


        $exercises = $this->getDoctrine()
            ->getRepository('App:Exercise')->findBy(array('lesson' => $lssn));

        // replace this example code with whatever you need
        return $this->render('teacher/lessonview.html.twig', array(
            'lssn' => $lssn,
            'exercises' => $exercises,
        ));
    }

    /**
     * @Route("{_locale}/teacher/upgrade/", name="upgradepage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function upgrade(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $classes = $em->getRepository('App:GradeStudent')->findUpgradeClassesList( $this->getUser() );

        $students = $em->getRepository('App:Student')->findUpgradeList( $this->getUser() );

        // replace this example code with whatever you need
        return $this->render('teacher/upgrade.html.twig', array(
            'menu' => '6','classes' => $classes,'students' => $students,
        ));
    }

    /**
     * @Route("{_locale}/teacher/help/", name="helppage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function help(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('teacher/help.html.twig', array(
            'menu' => '',
        ));
    }
}
