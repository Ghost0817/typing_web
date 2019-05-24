<?php

namespace App\Controller\Teacher;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Student;
use App\Entity\GradeStudent;

use App\Form\Model\AddAccountFromPartol;
use App\Form\Model\ImportStudent;

use App\Form\RegisterStudentFromPortal;
use App\Form\AccountAddFromPartol;
use App\Form\StudentImport;

class UsersController extends AbstractController
{
    /**
     * @Route("{_locale}/teacher/users/", name="userspage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function users(Request $request)
    {
        $clss = $this->getDoctrine()
            ->getRepository('App:Grade')->findBy(array('teacher'=> $this->getUser() ));

        // replace this example code with whatever you need
        return $this->render('teacher/users.html.twig', array(
            'menu' => '3',
            'classes' => $clss,
            'id' => null
        ));
    }

    /**
     * @Route("{_locale}/teacher/users/index/gid/{id}", name="usersgridpage")
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersgrid(Request $request, $id)
    {

        $clss = $this->getDoctrine()
            ->getRepository('App:Grade')->findBy(array('teacher'=> $this->getUser() ));

        return $this->render('teacher/users.html.twig', array(
            'menu' => '3',
            'classes' => $clss,
            'id' => $id
        ));
    }

    /**
     * @Route("{_locale}/teacher/create/", name="usercreatepage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usercreate(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $s_entity = new Student();
        $s_form = $this->createForm(RegisterStudentFromPortal::class, $s_entity);

        $a_entity = new AddAccountFromPartol();
        $a_form = $this->createForm(AccountAddFromPartol::class, $a_entity);

        if('POST' === $request->getMethod()) {

            if ($request->request->has('register_student_from_portal')) {
                $s_form->handleRequest($request);
                if ($s_form->isSubmitted() && $s_form->isValid()) {

                    $gradeandstudent = new GradeStudent();
                    $em = $this->getDoctrine()->getManager();

                    $keybr = $em->getRepository('App:MyKeybroard')->findOneById(1);

                    $password = $passwordEncoder->encodePassword($s_entity, $s_entity->getPassword());
                    $s_entity->setPassword($password);

                    $s_entity->setActivationkey($s_entity->getActivationkey());
                    $s_entity->setIsActive(1);
                    $s_entity->setLastlogin(new \DateTime("1970-07-08 11:00:00.00"));
                    $s_entity->setCreated(new \DateTime(date('Y-m-d H:i:s')));
                    $s_entity->setUpdated(new \DateTime(date('Y-m-d H:i:s')));
                    $s_entity->setGender('m');
                    $s_entity->setEnableSounds(false);
                    $s_entity->setMeasureSpeed('wpm');
                    $s_entity->setRole('ROLE_FREE');
                    $s_entity->setMykb($keybr);

                    $em->persist($s_entity);
                    $em->flush();

                    $gradeandstudent->setGrade($s_entity->getGrade());
                    $gradeandstudent->setCreated(new \DateTime(date('Y-m-d H:i:s')));
                    $gradeandstudent->setTeacher($this->getUser());
                    $gradeandstudent->setStudent($s_entity);

                    $em->persist($gradeandstudent);
                    $em->flush();

                    $this->addFlash(
                        's_success',
                        '<strong>Student Created!</strong> Student \''. $s_entity->getUsername() .'\' was created successfully. <br><br>Your students can log into Bicheech.com by going to <a href="http://www.bicheech.com/mn/student/start">bicheech.com/mn/student/start</a>'
                    );

                    return $this->redirectToRoute('usercreatepage');
                } else {
                    $errors = $s_form->getErrors();
                    //dump($s_form);die();
                }
            }

            if ($request->request->has('account_add_from_partol')) {
                $a_form->handleRequest($request);
                if ($a_form->isSubmitted() && $a_form->isValid()) {

                    $encoded = $passwordEncoder->encodePassword($this->getUser(), $a_entity->getPassword());
                    $a_entity->setPassword($encoded);

                    $addaccount = $this->getDoctrine()
                    ->getRepository('App:Student')
                    ->findAddAccount($a_entity->getUsername(),$a_entity->getPassword());

                    if( isset($addaccount) ) {

                        $isadded = $this->getDoctrine()
                        ->getRepository('App:GradeStudent')
                        ->findStudent($this->getUser(),$addaccount);

                        if( isset($isadded) ) {
                            $this->addFlash(
                            'a_error',
                            '<strong>Error Adding Student</strong> That account is already linked to your Portal!'
                            );

                            return $this->redirectToRoute('usercreatepage');
                        } else {

                            $gradeandstudent = new GradeStudent();
                            $gradeandstudent->setGrade($a_entity->getGrade());
                            $gradeandstudent->setCreated(new \DateTime(date('Y-m-d H:i:s')));
                            $gradeandstudent->setTeacher($this->getUser());
                            $gradeandstudent->setStudent($addaccount);

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($gradeandstudent);
                            $em->flush();

                            $this->addFlash(
                            'a_success',
                            '<strong>Student was Added Successfully!</strong> Student \''. $a_entity->getUsername() .'\' was added successfully. <br><br>All activity for that student is now available within your portal.'
                            );

                            return $this->redirectToRoute('usercreatepage');
                        }
                    } else {
                        $this->addFlash(
                        'a_error',
                        '<strong>Error Adding Student</strong> No account was found matching that username/password combination.'
                        );

                        return $this->redirectToRoute('usercreatepage');
                    }

                }
            }
        }

        // replace this example code with whatever you need
        return $this->render('teacher/usercreate.html.twig', array(
            'menu' => '3',
            's_form' => $s_form->createView(),
            'a_form' => $a_form->createView(),
        ));
    }

    /**
     * @Route("{_locale}/teacher/create/import/", name="usercreateimportpage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usercreateimport(Request $request, UserPasswordEncoderInterface $passwordEncoder, $_locale)
    {
        $i = 0;
        $data =array();
        $error = false;

        $a_entity = new ImportStudent();
        $a_form = $this->createForm(StudentImport::class, $a_entity);
        if('POST' === $request->getMethod()) {
            if ($request->request->has('student_import')) {
                $a_form->handleRequest($request);
                if ($a_form->isSubmitted() && $a_form->isValid()) {

                    $file = $a_form->get('file');

                    if (($handle = fopen($file->getData()->getRealPath(), "r")) !== FALSE) {
                        while(($row = fgetcsv($handle)) !== FALSE) {
                            if($i == 0){
                                $i = 1;
                            } else {
                                $isadded = $this->getDoctrine()
                                ->getRepository('App:Student')
                                ->findAccount($row[0]);

                                $keyboard = $this->getDoctrine()
                                    ->getRepository('App:MyKeybroard')
                                    ->findOneById(1);

                                if( isset($isadded)) {
                                    return $this->redirectToRoute('usercreatepage');
                                }

                                $student = new Student();
                                $student->setUsername($row[0]);

                                $encoded = $passwordEncoder->encodePassword($student, $row[1]);
                                $student->setPassword($encoded);

                                #$student->setPassword($row[1]);
                                $student->setFirstname($row[2]);
                                $student->setLastname($row[3]);
                                $student->setEmail($row[4]);
                                $student->setActivationkey($student->getActivationkey());
                                $student->setIsActive(1);
                                $student->setLastlogin(new \DateTime("1970-07-08 11:00:00.00"));
                                $student->setCreated(new \DateTime(date('Y-m-d H:i:s')));
                                $student->setUpdated(new \DateTime(date('Y-m-d H:i:s')));
                                $student->setGender('m');
                                $student->setEnableSounds(false);
                                $student->setMeasureSpeed('wpm');
                                $student->setRole('ROLE_FREE');
                                $student->setMykb($keyboard);

                                $em = $this->getDoctrine()->getManager();
                                $em->persist($student);
                                $em->flush();

                                $gradeandstudent = new GradeStudent();
                                $gradeandstudent->setGrade($a_entity->getGrade());
                                $gradeandstudent->setCreated(new \DateTime(date('Y-m-d H:i:s')));
                                $gradeandstudent->setTeacher($this->getUser());
                                $gradeandstudent->setStudent($student);

                                $em->persist($gradeandstudent);
                                $em->flush();
                            }
                        }
                    }
                    $msg = '';
                    if($_locale =='mn') {
                        $msg = $msg . '<strong>Сурагчдын үүсгэсэн</strong> Таны оюутны данс амжилттай импортлогдсон!<br><br>Таны оюутнууд Bicheech.com руу нэвтэрч <a href="http://www.bicheech.com/mn/student/start">bicheech.com/mn/student/start</a> руу орж болно';
                    } else {
                        $msg = $msg . '<strong>Students Created</strong> Your student accounts were imported successfully!<br><br>Your students can log into Bicheech.com by going to <a href="http://www.bicheech.com/mn/student/start">bicheech.com/mn/student/start</a>';
                    }
                    $this->addFlash(
                    'a_success',
                        $msg
                    );

                    return $this->redirectToRoute('usercreateimportpage');
                }
            }
        }

        // replace this example code with whatever you need
        return $this->render('teacher/usercreateimport.html.twig', array(
            'menu' => '3',
            'a_form' => $a_form->createView(),
        ));
    }

    /**
     * @Route("{_locale}/teacher/userdetails/index/id/{id}", name="userdetailspage", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function userdetail(Request $request,TranslatorInterface $translator, $_locale, $id)
    {
        $student = $this->getDoctrine()
            ->getRepository('App:Student')
            ->findOneById($id);
        if (!$student) {
            return $this->redirectToRoute('userspage');
        }

        $gradestudent = $this->getDoctrine()
            ->getRepository('App:GradeStudent')->findStudent($this->getUser()->getId(),$student->getId());

        $grade = $gradestudent->getGrade();

        $lssn1 = $this->getDoctrine()
            ->getRepository('App:Lesson')
            ->findViewStudentLessonInfo('english', $id);


        $lssn2 = $this->getDoctrine()
            ->getRepository('App:Lesson')
            ->findViewStudentLessonInfo('mongolian', $id);

        $tests1 = $this->getDoctrine()
            ->getRepository('App:Statustyping')
            ->findTypingTestProgess( $id, 'english');

        $tests2 = $this->getDoctrine()
            ->getRepository('App:Statustyping')
            ->findTypingTestProgess( $id, 'mongolian');

        $data1 =array();
        $data2 =array();
        $data3 =array();

        foreach ($lssn1 as $key) {
            $course = $_locale == 'en'? $key['cenTitle']:$key['cmnTitle'];
            $name = $_locale == 'en'? $key['enTitle']:$key['mnTitle'];
            $row['lessonID'] = $key['lessonID'].'';
            $row['course'] = $key['sortnum'] . '_' . $course;
            $row['name'] = $name . ( $key['isPremium'] == 1 ? ' (<strong style=\"font-weight: bold\">Premium Accounts Only</strong>)' : '' );
            $row['active'] = $key['active'] == '1' ? true : false;
            $row['disabled'] = $key['disabled'] == '1' ? true : false;
            $row['progress'] = $key['te'] == 0 ? '' : (round(($key['te'] * 100 ) / $key['ae']).'%');
            $row['spenttime'] = $key['time'] == 0 ? '' : round($key['time'] / 60) . ' '.$translator->trans('minutes');
            $row['grossSpeed'] = $key['correcthit'] == 0 ? '' : ($this->calculateSpeed($student->getMeasureSpeed(), $key['correcthit'], $key['time'], 0) ) . ' '.$translator->trans($student->getMeasureSpeed());
            $row['accuracy'] = $key['accuracy'] == 0 ? '' : round($key['accuracy']) . '%';
            $row['netSpeed'] = $key['correcthit'] == 0 ? '' : ($this->calculateSpeed($student->getMeasureSpeed(), $key['correcthit'], $key['time'], $key['mistakehit']) ) . ' '.$translator->trans($student->getMeasureSpeed());
            $row['lastTyped'] = $key['lastTyped'] == '' ? '1959-01-01 00:00:00' : $key['lastTyped'] ;
            $row['displayOrder'] = $key['displayOrder'] == '' ? null : $key['displayOrder'];

            array_push($data1, $row);
        }

        # starts improvement calucate
        $improve = 'N/A';
        $overallimprove = 0;
        $overallcount  = 0;

        for ($x = count($tests1) - 1; $x >= 0; $x--) {
            if($improve == 'N/A') {
                $tests1[$x]['improve'] = $improve;
                $tests1[$x]['overallimprove'] = $improve;
            } else {
                $tests1[$x]['improve'] = round( (  ( ($this->calculateSpeed($student->getMeasureSpeed(), $tests1[$x]['correcthit'], $tests1[$x]['time'], $tests1[$x]['mistakehit']) ) - $improve ) * 100 ) / $improve ) . '%';
                $divine = ($overallimprove / $overallcount);
                $tests1[$x]['overallimprove'] = round( (  ( ($this->calculateSpeed($student->getMeasureSpeed(), $tests1[$x]['correcthit'], $tests1[$x]['time'], $tests1[$x]['mistakehit']) ) - $divine ) * 100 ) / $divine ) . '%';
            }
            $improve = ($this->calculateSpeed($student->getMeasureSpeed(), $tests1[$x]['correcthit'], $tests1[$x]['time'], $tests1[$x]['mistakehit']) );
            $overallimprove = $overallimprove + ($this->calculateSpeed($student->getMeasureSpeed(), $tests1[$x]['correcthit'], $tests1[$x]['time'], $tests1[$x]['mistakehit']) );
            $overallcount++;
        }
        # ends improvement calucate

        foreach ($tests1 as $key) {
            $row['id'] = $key['id'];
            $date = new \DateTime('2000-01-01');
            $date = $key['createdAt'];
            $crossSpeed = ( $this->calculateSpeed($student->getMeasureSpeed(), $key['correcthit'], $key['time'], 0) );
            $netSpeed = ($this->calculateSpeed($student->getMeasureSpeed(), $key['correcthit'], $key['time'], $key['mistakehit']) );
            $row['testTime'] = $key['createdAt'] == '' ? '1959-01-01 00:00:00' : $date->format('Y-m-d H:i:s') ;
            $row['grossSpeed'] = $key['correcthit'] == 0 ? '' : $crossSpeed . ' '.$translator->trans($student->getMeasureSpeed());
            $row['accuracy'] = $key['accuracy'] == 0 ? '' : (round($key['accuracy']) . '%');
            $row['netSpeed'] = $key['correcthit'] == 0 ? '' : $netSpeed . ' '.$translator->trans($student->getMeasureSpeed());
            $row['spentTime'] = $key['time'] == 0 ? '' : round($key['time'] / 60) . ' '.$translator->trans('minutes');
            $row['improve'] = $key['improve'];
            $row['overallimprove'] = $key['overallimprove'];

            array_push($data2, $row);
        }

        // replace this example code with whatever you need
        return $this->render('teacher/userdetails.html.twig', array(
            'menu' => '',
            'lessonData' => json_encode($data1),
            'testsData' => json_encode($data2),
            'loginData' => json_encode($data3),
            'student' => $student,
            'grade' => $grade
        ));
    }

    public function calculateSpeed($type, $characters, $seconds, $errors){

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
