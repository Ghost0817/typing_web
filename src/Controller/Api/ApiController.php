<?php
/**
 * Created by PhpStorm.
 * User: 22cc3355
 * Date: 1/30/2019
 * Time: 9:31 PM
 */



#INSERT INTO `faqs` (`id`, `mn_title`, `en_title`, `mn_body`, `en_body`, `sortnum`) VALUES
#(1, 'How much does Bicheech.com cost?', 'How much does Bicheech.com cost?', 'Bicheech.com is free! Make sure to register for a free account to save your typing progress and statistics! Bicheech.com displays fun and interesting ads to help pay for the cost of building and maintaining Bicheech.com. If you would prefer not to see advertisements, you can upgrade to a Premium Account and you will never see another ad again.', 'Bicheech.com is free! Make sure to register for a free account to save your typing progress and statistics! Bicheech.com displays fun and interesting ads to help pay for the cost of building and maintaining Bicheech.com. If you would prefer not to see advertisements, you can upgrade to a Premium Account and you will never see another ad again.', 1),
#(2, 'I am trying to add my students but I get an error message that says username already taken.', 'I am trying to add my students but I get an error message that says username already taken.', 'We use a shared student username database. When you try to create a username it is checked against every other student in our system, just like trying to register for an email address with Google. You will find that common usernames are “already in use” by other students at other schools. Make sure the names are unique as possible to avoid this.', 'We use a shared student username database. When you try to create a username it is checked against every other student in our system, just like trying to register for an email address with Google. You will find that common usernames are “already in use” by other students at other schools. Make sure the names are unique as possible to avoid this.', 2),
#(3, 'How do students reset lost passwords?', 'How do students reset lost passwords?', 'You can reset your students\' passwords from the Classes page of your Teacher Portal.', 'You can reset your students\' passwords from the Classes page of your Teacher Portal.', 3),
#(4, 'I purchased Ad-Free licenses. Why do I still see ads on the students accounts?', 'I purchased Ad-Free licenses. Why do I still see ads on the students accounts?', 'You will need to assign the licenses to your students. To assign the licenses to your students click on the Manage Premium Licenses link at the very top of your teacher portal. Once there you will have the ability to upgrade and downgrade your students.', 'You will need to assign the licenses to your students. To assign the licenses to your students click on the Manage Premium Licenses link at the very top of your teacher portal. Once there you will have the ability to upgrade and downgrade your students.', 4),
#(5, 'My student has two teachers who use Bcheech.com. How can I add another teacher so they can also view students progress.', 'My student has two teachers who use Bcheech.com. How can I add another teacher so they can also view students progress.', 'Bicheech.com’s Teacher Portal only supports a single login per account.', 'Bicheech.com’s Teacher Portal only supports a single login per account.', 5),
#(6, 'I accidentally deleted my students account, can I recover the data from that account?', 'I accidentally deleted my students account, can I recover the data from that account?', 'Unfortunately once an account has been deleted the data can not be recovered.', 'Unfortunately once an account has been deleted the data can not be recovered.', 6),
#(7, 'How is the time spent typing calculated?', 'How is the time spent typing calculated?', 'Time spent typing is calculated from the time the student starts and stops typing on the keyboard in a lesson screen. It also includes the timed tests. The time does not include the time it takes to move from one lesson to another or time spent on games.', 'Time spent typing is calculated from the time the student starts and stops typing on the keyboard in a lesson screen. It also includes the timed tests. The time does not include the time it takes to move from one lesson to another or time spent on games.', 7),
#(8, 'Can I disable the backspace key?', 'Can I disable the backspace key?', 'The backspace key can not be disabled.', 'The backspace key can not be disabled.', 8),
#(9, 'I do not want my students to view the Class Scoreboard, can this be disabled?', 'I do not want my students to view the Class Scoreboard, can this be disabled?', 'Yes, the Scoreboard can be disabled from within your Teacher Portal, on the Account page, under Enable Class Scoreboard.', 'Yes, the Scoreboard can be disabled from within your Teacher Portal, on the Account page, under Enable Class Scoreboard.', 9);

# INSERT INTO `tip` (`id`, `name`) VALUES
#(1, 'Typing speed is calculated in two ways: Gross Speed is your overall words typed per minute. A word is 5 typed characters.
#Net Speed subtracts one word for each incorrectly typed character.'),
#(2, 'The typing test is a great way to track your progress. Take it twice to begin graphing your progress on the Statistics page.
#We sace your last 10 scores to show improvement over time.'),
#(3, 'Register with Bicheech to track your progress, access additional content and compete for your place in the Hall of Fame.'),
#(4, 'Bicheech learns your most troublesome keys as you type the lessons. Once Bicheech has learned which keys cause you the most
#brouble a custom generated lesson can be accessed for practicing just your top problem keys!');

namespace App\Controller\Api;

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

class ApiController extends AbstractController
{

    /**
     * @Route("{_locale}/teacher/radar/fetch", name="radarfetchpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function radarfetch(Request $request)
    {
        $called = $this->getDoctrine()
            ->getRepository('App:Student')
            ->findOnlineAccount($this->getUser());
        //var_dump($called);die();
        $data = array();

        foreach ($called as $key => $value) {
            $data['lastActivity'] = $value->getLastActivityAt()->getTimestamp();
            $data['page'] = split(",", $value->getLastAction())[0];
            $extra = array();
            if(count(split(",", $value->getLastAction())) >= 2){
                $extra = array('value' => split(",", $value->getLastAction())[1]);
            }
            $data['extra'] = $extra;
            $data = array($value->getId() => $data);
        }


        //{"data":{"14502999":{"lastActivity":1462674289,"page":"GAMES","extra":{}}},"success":true}
        //{data: {14502999: {lastActivity: 1462691496, page: "PLAYGAME", extra: {value: "23"}}}, success: true}

        $response = new Response();
        $response->setContent(json_encode(array(
            'data'     => $data,
            'success' => true,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/reports/run/", name="reportrunpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function reportrunAction(Request $request,TranslatorInterface $translator)
    {
        $dateRange  = $request->request->get('dateRange');
        $report     = $request->request->get('report');
        $gaGroupID  = $request->request->get('gaGroupID');
        $startDate  = $request->request->get('startDate');
        $endDate    = $request->request->get('endDate');
        $date       = $request->request->get('date');
        $lang       = $request->request->get('lang');
        $locale = $request->getLocale();

        $data = array();
        $fields = array();
        $columns = array();
        $success = true;
        $title = "";

        if($dateRange == 'today') {
            $startDate = (new \DateTime("now"))->format('Y-m-d');
            $endDate   = (new \DateTime("now"))->format('Y-m-d');
        }
        if($dateRange == 'yesterday') {
            $startDate = (new \DateTime("yesterday"))->format('Y-m-d');
            $endDate   = (new \DateTime("yesterday"))->format('Y-m-d');
        }
        if($dateRange == 'thisweek') {
            $startDate = date( 'Y-m-d', strtotime('this week'));
            $endDate   = (new \DateTime("now"))->format('Y-m-d');
        }
        if($dateRange == 'lastweek') {
            $startDate = date( 'Y-m-d', strtotime('Last Week'));
            $endDate   = date( 'Y-m-d', strtotime('Last Sunday'));
        }
        if($dateRange == 'overall') {
            $startDate = '';
            $endDate   = '';
        }
        if($dateRange == 'custom') {
            //come code is here ...
        }

        $fields[0]['name'] = 'id';
        $fields[0]['type'] = 'int';

        $fields[1]['name'] = 'username';
        $fields[1]['type'] = 'string';

        $fields[2]['name'] = 'firstname';
        $fields[2]['type'] = 'string';

        $fields[3]['name'] = 'lastname';
        $fields[3]['type'] = 'string';

        $fields[4]['name'] = 'class';
        $fields[4]['type'] = 'string';

        $columns[0]['dataIndex'] = 'username';
        $columns[0]['header'] = $translator->trans('Username');
        $columns[0]['sortable'] = true;

        $columns[1]['dataIndex'] = 'firstname';
        $columns[1]['header'] = $translator->trans('First Name');
        $columns[1]['sortable'] = true;

        $columns[2]['dataIndex'] = 'lastname';
        $columns[2]['header'] = $translator->trans('Last Name');
        $columns[2]['sortable'] = true;

        $columns[3]['dataIndex'] = 'class';
        $columns[3]['header'] = $translator->trans('Class');
        $columns[3]['sortable'] = true;

        if($report == 'exercises') {
            $stts = $this->getDoctrine()
                ->getRepository('App:Statustyping')
                ->findExercisesReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate);

            $title = $translator->trans('Student Progress Overview').' '. $startDate .' to '. $endDate .' for ';
            if($gaGroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($gaGroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($gaGroupID != 'all' && $gaGroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('App:Grade')->findOneById($gaGroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';

            foreach ($stts as $key) {
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                array_push($data, $key);
            }

            $fields[5]['name'] = 'seconds';
            $fields[5]['type'] = 'int';

            $fields[6]['name'] = 'total';
            $fields[6]['type'] = 'int';

            $fields[7]['name'] = 'gross';
            $fields[7]['type'] = 'int';

            $fields[8]['name'] = 'accuracy';
            $fields[8]['type'] = 'int';

            $fields[9]['name'] = 'net';
            $fields[9]['type'] = 'int';

            $columns[4]['dataIndex'] = 'seconds';
            $columns[4]['header'] = $translator->trans('Time Spent');
            $columns[4]['sortable'] = true;
            $columns[4]['time'] = true;

            $columns[5]['dataIndex'] = 'total';
            $columns[5]['header'] = $translator->trans('Exercises Completed');
            $columns[5]['sortable'] = true;

            $columns[6]['dataIndex'] = 'gross';
            $columns[6]['header'] = $translator->trans('Avg Gross Speed');
            $columns[6]['sortable'] = true;
            $columns[6]['speed'] = true;

            $columns[7]['dataIndex'] = 'accuracy';
            $columns[7]['header'] = $translator->trans('Avg Accuracy');
            $columns[7]['sortable'] = true;
            $columns[7]['acc'] = true;

            $columns[8]['dataIndex'] = 'net';
            $columns[8]['header'] = $translator->trans('Avg Net Speed');
            $columns[8]['sortable'] = true;
            $columns[8]['speed'] = true;
        }
        if($report == 'detailed'){
            $stts = $this->getDoctrine()
                ->getRepository('App:Statustyping')
                ->findDetailedReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate)
            ;
            $title = $translator->trans('Detailed by Lesson').' '. $startDate .' to '. $endDate .' for ';
            if($gaGroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($gaGroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($gaGroupID != 'all' && $gaGroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('App:Grade')->findOneById($gaGroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';

            foreach ($stts as $key) {
                $course = $locale == 'en'? $key['cenTitle']:$key['cmnTitle'];
                $name = $locale == 'en'? $key['enTitle']:$key['mnTitle'];
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                $key['course'] = $course;
                $key['name'] = $name;
                $key['progress'] = round(($key['total'] * 100) / $key['progress']);

                unset($key['cenTitle']);
                unset($key['cmnTitle']);
                unset($key['enTitle']);
                unset($key['mnTitle']);

                array_push($data, $key);
            }

            $fields[5]['name'] = 'course';
            $fields[5]['type'] = 'string';

            $fields[6]['name'] = 'name';
            $fields[6]['type'] = 'string';

            $fields[7]['name'] = 'progress';
            $fields[7]['type'] = 'int';

            $fields[8]['name'] = 'seconds';
            $fields[8]['type'] = 'int';

            $fields[9]['name'] = 'total';
            $fields[9]['type'] = 'int';

            $fields[10]['name'] = 'gross';
            $fields[10]['type'] = 'int';

            $fields[11]['name'] = 'accuracy';
            $fields[11]['type'] = 'int';

            $fields[12]['name'] = 'net';
            $fields[12]['type'] = 'int';

            $columns[4]['dataIndex'] = 'course';
            $columns[4]['header'] = $translator->trans('Course');
            $columns[4]['sortable'] = true;

            $columns[5]['dataIndex'] = 'name';
            $columns[5]['header'] = $translator->trans('Lesson');
            $columns[5]['sortable'] = true;

            $columns[6]['dataIndex'] = 'progress';
            $columns[6]['header'] = $translator->trans('Current Progress');
            $columns[6]['sortable'] = true;
            $columns[6]['acc'] = true;

            $columns[7]['dataIndex'] = 'seconds';
            $columns[7]['header'] = $translator->trans('Time Spent');
            $columns[7]['sortable'] = true;
            $columns[7]['time'] = true;

            $columns[8]['dataIndex'] = 'total';
            $columns[8]['header'] = $translator->trans('Exercises Completed');
            $columns[8]['sortable'] = true;

            $columns[9]['dataIndex'] = 'gross';
            $columns[9]['header'] = $translator->trans('Avg Gross Speed');
            $columns[9]['sortable'] = true;
            $columns[9]['speed'] = true;

            $columns[10]['dataIndex'] = 'accuracy';
            $columns[10]['header'] = $translator->trans('Avg Accuracy');
            $columns[10]['sortable'] = true;
            $columns[10]['acc'] = true;

            $columns[11]['dataIndex'] = 'net';
            $columns[11]['header'] = $translator->trans('Avg Net Speed');
            $columns[11]['sortable'] = true;
            $columns[11]['speed'] = true;
        }
        if($report == 'typingtests') {
            $stts = $this->getDoctrine()
                ->getRepository('App:Statustyping')
                ->findTypingtestReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate);
            $title = $translator->trans('Typing Tests').' '. $startDate .' to '. $endDate .' for ';
            if($gaGroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($gaGroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($gaGroupID != 'all' && $gaGroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('App:Grade')->findOneById($gaGroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';

            foreach ($stts as $key) {
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                array_push($data, $key);
            }

            $fields[5]['name'] = 'seconds';
            $fields[5]['type'] = 'int';

            $fields[6]['name'] = 'total';
            $fields[6]['type'] = 'int';

            $fields[7]['name'] = 'gross';
            $fields[7]['type'] = 'int';

            $fields[8]['name'] = 'accuracy';
            $fields[8]['type'] = 'int';

            $fields[9]['name'] = 'net';
            $fields[9]['type'] = 'int';

            $columns[4]['dataIndex'] = 'seconds';
            $columns[4]['header'] = $translator->trans('Time Spent');
            $columns[4]['sortable'] = true;
            $columns[4]['time'] = true;

            $columns[5]['dataIndex'] = 'total';
            $columns[5]['header'] = $translator->trans('Times Taken');
            $columns[5]['sortable'] = true;

            $columns[6]['dataIndex'] = 'gross';
            $columns[6]['header'] = $translator->trans('Avg Gross Speed');
            $columns[6]['sortable'] = true;
            $columns[6]['speed'] = true;

            $columns[7]['dataIndex'] = 'accuracy';
            $columns[7]['header'] = $translator->trans('Avg Accuracy');
            $columns[7]['sortable'] = true;
            $columns[7]['acc'] = true;

            $columns[8]['dataIndex'] = 'net';
            $columns[8]['header'] = $translator->trans('Avg Net Speed');
            $columns[8]['sortable'] = true;
            $columns[8]['speed'] = true;
        }
        if($report == 'scoreboard') {
            // only this week in this case.
            $startDate = date( 'Y-m-d', strtotime('this week'));
            $endDate   = (new \DateTime("now"))->format('Y-m-d');

            $stts = $this->getDoctrine()
                ->getRepository('App:Statustyping')
                ->findTypingtestReport( $lang, $gaGroupID, $dateRange, $startDate, $endDate);
            $title = $translator->trans('Class Scoreboard').' '. $startDate .' to '. $endDate .' for ';
            if($gaGroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($gaGroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($gaGroupID != 'all' && $gaGroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('App:Grade')->findOneById($gaGroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';
            foreach ($stts as $key) {
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                array_push($data, $key);
            }

            $fields[5]['name'] = 'seconds';
            $fields[5]['type'] = 'int';

            $fields[6]['name'] = 'total';
            $fields[6]['type'] = 'int';

            $fields[7]['name'] = 'gross';
            $fields[7]['type'] = 'int';

            $fields[8]['name'] = 'accuracy';
            $fields[8]['type'] = 'int';

            $fields[9]['name'] = 'net';
            $fields[9]['type'] = 'int';

            $columns[4]['dataIndex'] = 'seconds';
            $columns[4]['header'] = $translator->trans('Time Spent');
            $columns[4]['sortable'] = true;
            $columns[4]['time'] = true;

            $columns[5]['dataIndex'] = 'total';
            $columns[5]['header'] = $translator->trans('Times Taken');
            $columns[5]['sortable'] = true;

            $columns[6]['dataIndex'] = 'gross';
            $columns[6]['header'] = $translator->trans('Avg Gross Speed');
            $columns[6]['sortable'] = true;
            $columns[6]['speed'] = true;

            $columns[7]['dataIndex'] = 'accuracy';
            $columns[7]['header'] = $translator->trans('Avg Accuracy');
            $columns[7]['sortable'] = true;
            $columns[7]['acc'] = true;

            $columns[8]['dataIndex'] = 'net';
            $columns[8]['header'] = $translator->trans('Avg Net Speed');
            $columns[8]['sortable'] = true;
            $columns[8]['speed'] = true;
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => $success,
            'data' => array(
                'data'    => $data,
                'fields'  => $fields,
                'columns' => $columns,
                'limited' => false,
            ),
            'title' => $title,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/reports/run/export/date/{date}/dateRange/{dateRange}/endDate/{endDate}/startDate/{startDate}/GroupID/{GroupID}/lang/{lang}/report/{report}/", name="reportexportpage", methods={"POST"}, defaults={
     *     "endDate": "",
     *     "startDate": "",
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Route("{_locale}/teacher/reports/run/export/date/{date}/dateRange/{dateRange}/endDate//startDate//GroupID/{GroupID}/lang/{lang}/report/{report}/", name="reportexportpage1", methods={"POST"}, defaults={
     *     "endDate": "",
     *     "startDate": "",
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Route("{_locale}/teacher/reports/run/export/date/{date}/dateRange/{dateRange}/endDate/{endDate}/startDate//GroupID/{GroupID}/lang/{lang}/report/{report}/", name="reportexportpage2", methods={"POST"}, defaults={
     *     "endDate": "",
     *     "startDate": "",
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Route("{_locale}/teacher/reports/run/export/date/{date}/dateRange/{dateRange}/endDate//startDate/{startDate}/GroupID/{GroupID}/lang/{lang}/report/{report}/", name="reportexportpage3", methods={"POST"}, defaults={
     *     "endDate": "",
     *     "startDate": "",
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     * @throws \Exception
     */
    public function reportexportAction(Request $request,TranslatorInterface $translator, $date, $dateRange, $endDate, $startDate, $GroupID, $lang, $report)
    {
        if($dateRange == 'today') {
            $startDate = (new \DateTime("now"))->format('Y-m-d');
            $endDate   = (new \DateTime("now"))->format('Y-m-d');
        }
        if($dateRange == 'yesterday') {
            $startDate = (new \DateTime("yesterday"))->format('Y-m-d');
            $endDate   = (new \DateTime("yesterday"))->format('Y-m-d');
        }
        if($dateRange == 'thisweek') {
            $startDate = date( 'Y-m-d', strtotime('this week'));
            $endDate   = (new \DateTime("now"))->format('Y-m-d');
        }
        if($dateRange == 'lastweek') {
            $startDate = date( 'Y-m-d', strtotime('Last Week'));
            $endDate   = date( 'Y-m-d', strtotime('Last Sunday'));
        }
        if($dateRange == 'overall') {
            $startDate = '';
            $endDate   = '';
        }
        if($dateRange == 'custom') {
            //come code is here ...
        }

        $response = new StreamedResponse();
        $results = array();
        $title = "";
        $columns = [];
        $locale = $request->getLocale();

        if($report == 'exercises') {
            $stts = $this->getDoctrine()
                ->getRepository('App:Statustyping')
                ->findExercisesReport( $lang, $GroupID, $dateRange, $startDate, $endDate);

            $title = $translator->trans('Student Progress Overview').' '. $startDate .' to '. $endDate .' for ';
            if($GroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($GroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($GroupID != 'all' && $GroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('App:Grade')->findOneById($GroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';
            foreach ($stts as $key) {
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                $key['accuracy'] = round($key['accuracy']);
                array_push($results, $key);
            }

            $columns = [
                'username'  => ['username', $translator->trans('Username')],
                'firstname' => ['firstname', $translator->trans('First Name')],
                'lastname'  => ['lastname', $translator->trans('Last Name')],
                'class'     => ['class', $translator->trans('Class')],
                'seconds'   => ['seconds', $translator->trans('Time Spent')],
                'total'     => ['total', $translator->trans('Exercises Completed')],
                'gross'     => ['gross', $translator->trans('Avg Gross Speed')],
                'accuracy'  => ['accuracy', $translator->trans('Avg Accuracy')],
                'net'       => ['net', $translator->trans('Avg Net Speed')]
            ];
        }
        if($report == 'detailed') {
            $stts = $this->getDoctrine()
                ->getRepository('AppBundle:Statustyping')
                ->findDetailedReport( $lang, $GroupID, $dateRange, $startDate, $endDate)
            ;
            $title = $translator->trans('Detailed by Lesson').' '. $startDate .' to '. $endDate .' for ';
            if($GroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($GroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($GroupID != 'all' && $GroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('AppBundle:Grade')->findOneById($GroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';
            foreach ($stts as $key) {
                $course = $locale == 'en'? $key['cenTitle']:$key['cmnTitle'];
                $name = $locale == 'en'? $key['enTitle']:$key['mnTitle'];
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                $key['course'] = $course;
                $key['name'] = $name;
                $key['progress'] = round(($key['total'] * 100) / $key['progress']);

                unset($key['cenTitle']);
                unset($key['cmnTitle']);
                unset($key['enTitle']);
                unset($key['mnTitle']);

                array_push($results, $key);
            }

            $columns = [
                'username'  => ['username',  $translator->trans('Username')],
                'firstname' => ['firstname', $translator->trans('First Name')],
                'lastname'  => ['lastname',  $translator->trans('Last Name')],
                'class'     => ['class',     $translator->trans('Class')],
                'course'    => ['course',    $translator->trans('Course')],
                'name'      => ['name',      $translator->trans('Lesson')],
                'progress'  => ['progress',  $translator->trans('Current Progress')],
                'seconds'   => ['seconds',   $translator->trans('Time Spent')],
                'total'     => ['total',     $translator->trans('Exercises Completed')],
                'gross'     => ['gross',     $translator->trans('Avg Gross Speed')],
                'accuracy'  => ['accuracy',  $translator->trans('Avg Accuracy')],
                'net'       => ['net',       $translator->trans('Avg Net Speed')]
            ];
        }
        if($report == 'typingtests') {
            $stts = $this->getDoctrine()
                ->getRepository('AppBundle:Statustyping')
                ->findTypingtestReport( $lang, $GroupID, $dateRange, $startDate, $endDate);
            $title = $translator->trans('Typing Tests').' '. $startDate .' to '. $endDate .' for ';
            if($GroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($GroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($GroupID != 'all' && $GroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('AppBundle:Grade')->findOneById($GroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';
            foreach ($stts as $key) {
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                array_push($results, $key);
            }
        }
        if($report == 'scoreboard') {
            // only this week in this case.
            $startDate = date( 'Y-m-d', strtotime('this week'));
            $endDate   = (new \DateTime("now"))->format('Y-m-d');

            $stts = $this->getDoctrine()
                ->getRepository('AppBundle:Statustyping')
                ->findTypingtestReport( $lang, $GroupID, $dateRange, $startDate, $endDate);
            $title = $translator->trans('Class Scoreboard').' '. $startDate .' to '. $endDate .' for ';
            if($GroupID == 'all'){
                $title = $title.$translator->trans('All Students');
            }
            if($GroupID == 'ungrouped'){
                $title = $title.$translator->trans('Ungrouped Students');
            }
            if($GroupID != 'all' && $GroupID != 'ungrouped'){
                $clss = $this->getDoctrine()
                    ->getRepository('AppBundle:Grade')->findOneById($GroupID);
                $title = $title.'class '.$clss->getName();
            }
            $title = $title.' ['.count($stts).' '.$translator->trans('records').']';
            foreach ($stts as $key) {
                $key['gross'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], 0);
                $key['net'] = $this->calculateSpeed('wpm', $key['totalTyped'], $key['seconds'], $key['errors']);
                array_push($results, $key);
            }
        }

        $response->setCallback(function() use ($results, $columns, $title) {
            $handle = fopen('php://output', 'w');

            $data = array(
                $title,
            );
            fputcsv($handle, $data, ',');
            fputcsv($handle, array_column($columns, 1), ',');

            foreach ($results as $row) {
                $fileRow = [];
                foreach ($columns as $values) {
                    $fileRow[] = $row[$values[0]];
                }
                fputcsv($handle, $fileRow, ',');
            }

            fclose($handle);
        }
        );

        $response->setStatusCode(200);

        $response->headers->set('Cache-Control','public');
        $response->headers->set('Content-Type','application/octet-stream');
        $response->headers->set('Content-Type','text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename=sainaa.csv;');
        $response->headers->set('Content-Transfer-Encoding','binary');
        /*
                  $response->headers->set('Content-Type', 'application/octet-stream');
                  $response->headers->set('Content-Disposition','attachment; filename="'.$report.'.csv"');
                  $response->headers->set('Content-Transfer-Encoding', 'binary');
                  $response->headers->set('Expires', '0');
                  $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
                  $response->headers->set('Pragma', 'public');
        */
        $response->send();

        return $response;
    }


    /**
     * @Route("{_locale}/tutor/student/clear/", name="status_del", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function teststtsdel(Request $request){
        $em = $this->getDoctrine()->getManager();
        $usr = $this->getUser();

        $id = $request->request->get('id');
        $lang = $request->request->get('lang');
        $success = true;

        if($id == 'PROBLEM_KEYS')
        {
            $status = $em->getRepository('App:Statustyping')->findKeysDel($usr, $lang);
            foreach ($status as $item) {
                $item->setErrorKeys('{}');
                $item->setAllKeys('{}');
                $em->persist($item);
                $em->flush();
                $success = true;
            }
        }
        if($id == 'TYPING_TEST')
        {
            $status = $em->getRepository('App:Statustyping')->findTestDel($usr, $lang);
            foreach ($status as $item) {
                $em->remove($item);
                $em->flush();
                $success = true;
            }
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => $success
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/student/statistics/{lang}/export/id/{type}/lid", name="tutor_statistics_export", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function export(Request $request, $lang, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->getUser();
        $locale = $request->getLocale();
        $columns = [];
        $response = new StreamedResponse();

        if ($type == 'SUMMARY'){
            $status = $em->getRepository('App:Statustyping')->findSummaryGraph($usr->getId(), $lang);

            $columns = [
                'lesson'     => ['lesson', 'Lessons'],
                'course'     => ['course', 'Course'],
                'grossSpeed' => ['grossSpeed', 'Gross Speed'],
                'netSpeed'   => ['netSpeed', 'Net Speed'],
                'acc'        => ['acc', 'Accuracy'],
                'progress'   => ['progress', 'Progress']
            ];

            $response->setCallback(function() use ($status, $columns, $usr) {

                $handle = fopen('php://output', 'w');

                $data = array(
                    'Progress Summary for '. $usr->getUsername() .' ( '. $usr->getFirstname().' '. $usr->getLastname() .' ) ['. count($status) .' Records]',
                );
                fputcsv($handle, $data, ',');
                fputcsv($handle, array_column($columns, 1), ',');

                foreach($status as $item){
                    $fileRow = [];
                    foreach ($columns as $values) {
                        if($values[0] == 'lesson'){
                            $fileRow[] = $item['enTitle'];
                        }
                        if($values[0] == 'course') {
                            $fileRow[] = $item['cenTitle'];
                        }
                        if($values[0] == 'grossSpeed') {
                            $fileRow[] = $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], 0).' '.$usr->getMeasureSpeed();
                        }
                        if($values[0] == 'netSpeed') {
                            $fileRow[] = $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit']).' '.$usr->getMeasureSpeed();
                        }
                        if($values[0] == 'acc') {
                            $fileRow[] = $item['acc'].'%';
                        }
                        if($values[0] == 'progress') {
                            $fileRow[] = round($item['ce']/$item['ae']*100).'%';
                        }
                    }
                    fputcsv($handle, $fileRow, ',');
                }

                fclose($handle);
            }
            );

        }

        if ($type == 'ACTIVITY'){
            $status = $em->getRepository('App:Statustyping')->findActivityGraph($usr->getId(), $lang);

            $columns = [
                'loginDate'  => ['loginDate', 'Login Date'],
                'dayAgo'     => ['dayAgo', 'Days Ago'],
                'startTime'  => ['startTime', 'Start Time'],
                'time'       => ['time', 'Time Spent (seconds)'],
                'eType'      => ['eType', 'Exercises Typed']
            ];

            $response->setCallback(function() use ($status, $columns, $usr) {

                $handle = fopen('php://output', 'w');

                $data = array(
                    'Time Spent Typing (by day) for '. $usr->getUsername() .' ( '. $usr->getFirstname().' '. $usr->getLastname() .' ) ['. count($status) .' Records]',
                );
                fputcsv($handle, $data, ',');
                fputcsv($handle, array_column($columns, 1), ',');

                foreach($status as $item){
                    $fileRow = [];
                    $datetime1 = new \DateTime("now");
                    $datetime2 = new \DateTime($item['lastlogin']);
                    $interval = $datetime1->diff($datetime2);
                    foreach ($columns as $values) {
                        if($values[0] == 'loginDate'){
                            $fileRow[] = $item['lastlogin'];
                        }
                        if($values[0] == 'dayAgo') {
                            $fileRow[] = str_replace( "-", "", $interval->format('%R%a'));
                        }
                        if($values[0] == 'startTime') {
                            $fileRow[] = $datetime2->format('g:i A');
                        }
                        if($values[0] == 'time') {
                            $fileRow[] = $item['spenttime'];
                        }
                    }
                    fputcsv($handle, $fileRow, ',');
                }

                fclose($handle);
            }
            );
        }

        if ($type == 'PROBLEM_KEYS'){
            $status = $em->getRepository('App:Statustyping')->findProblemKeyGraph($usr->getId(), $lang);

            $charsstatus = array();
            $delchars = array();

            $allKeys = array();
            foreach ($status as $item) {
                $a1 = json_decode( $item['allKeys'], true );
                $allKeys = array_merge_recursive( $allKeys, $a1 );
            }

            foreach($allKeys as $item => $values){
                $i = 0;
                if (is_array($values) || is_object($values))
                {
                    foreach($values as $hits){
                        $i += $hits;
                    }
                } else {
                    $i = $values;
                }
                $charsstatus[str_replace("key-","",$item)] = $i;
            }

            $errorKeys = array();
            foreach ($status as $item) {
                $a1 = json_decode( $item['errorKeys'], true );
                $errorKeys = array_merge_recursive( $errorKeys, $a1 );
            }

            foreach($errorKeys as $item => $values){
                $i = 0;
                if (is_array($values) || is_object($values))
                {
                    foreach($values as $hits){
                        $i += $hits;
                    }
                } else {
                    $i = $values;
                }
                $charsstatus[str_replace("key-","",$item)] = (int) round( ($i*100) / $charsstatus[str_replace("key-","",$item)] );
                $delchars[str_replace("key-","",$item)] = $i;
            }

            foreach(array_diff($charsstatus, $delchars) as $item => $values){
                unset($charsstatus[$item]);
            }

            $columns = [
                'key'     => ['key', 'Key'],
                'percent' => ['percent', 'Percent']
            ];

            $response->setCallback(function() use ($charsstatus, $columns, $usr) {

                $handle = fopen('php://output', 'w');

                $data = array(
                    'Your Problem Keys for '. $usr->getUsername() .' ( '. $usr->getFirstname().' '. $usr->getLastname() .' ) ['. count($charsstatus) .' Records]',
                );
                fputcsv($handle, $data, ',');
                fputcsv($handle, array_column($columns, 1), ',');

                foreach($charsstatus as $item => $val){
                    $fileRow = [];
                    foreach ($columns as $values) {
                        if($values[0] == 'key'){
                            $fileRow[] = strtoupper($item);
                        }
                        if($values[0] == 'percent') {
                            $fileRow[] = $val.'%';
                        }
                    }
                    fputcsv($handle, $fileRow, ',');
                }

                fclose($handle);
            }
            );
        }

        if ($type == 'TYPING_TEST'){
            $status = $em->getRepository('App:Statustyping')->findTypingTestGraph($usr->getId(), $lang);

            $columns = [
                'time'       => ['time', 'Test Time'],
                'grossSpeed' => ['grossSpeed', 'Gross Speed'],
                'accuracy'   => ['accuracy', 'Accuracy'],
                'netSpeed'   => ['netSpeed', 'Net Speed']
            ];

            $response->setCallback(function() use ($status, $columns, $usr) {

                $handle = fopen('php://output', 'w');

                $data = array(
                    'Typing Test Results for '. $usr->getUsername() .' ( '. $usr->getFirstname().' '. $usr->getLastname() .' ) ['. count($columns) .' Records]',
                );
                fputcsv($handle, $data, ',');
                fputcsv($handle, array_column($columns, 1), ',');

                foreach ($status as $item) {
                    $fileRow = [];
                    foreach ($columns as $values) {
                        if($values[0] == 'time'){
                            $fileRow[] = $item['time'];
                        }
                        if($values[0] == 'grossSpeed') {
                            $fileRow[] = $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], 0);
                        }
                        if($values[0] == 'accuracy'){
                            $fileRow[] = $item['acc'];
                        }
                        if($values[0] == 'netSpeed'){
                            $fileRow[] = $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit']);
                        }
                    }
                    fputcsv($handle, $fileRow, ',');
                }

                fclose($handle);
            }
            );

        }

        $response->setStatusCode(200);
        $response->headers->set('Content-Encoding', 'UTF-8');
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="'.$type.'.csv"');

        $response->send();

        return $response;
    }

    /**
     * @Route("{_locale}/student/statistics/{lang}/graph/id/{type}/lid/", name="tutor_statistics_graph", methods={"GET"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function graph(Request $request,TranslatorInterface $translator, $lang, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $usr = $this->getUser();
        $locale = $request->getLocale();
        $result = array(); // setting empty data.

        if(is_null($usr)){
            return $this->redirect($this->generateUrl('student_login'));
        }

        if ($type == 'SUMMARY'){
            $status = $em->getRepository('App:Statustyping')->findSummaryGraph($usr->getId(), $lang);
            $result = array(
                'chart' => array(
                    "xAxisname"=> $translator->trans('Lessons'),
                    "pYAxisName"=> $translator->trans('Speed ('.$usr->getMeasureSpeed().')'),
                    "sYAxisName"=> $translator->trans('Accuracy').' %',
                    "numberPrefix"=> $translator->trans($usr->getMeasureSpeed()),
                    "sNumberSuffix"=> "%",
                    "sYAxisMaxValue"=> "100",
                    "sYAxisMinValue"=> "0",
                    "paletteColors"=> "#25C6D0,#25d085,#25d02f",
                    "bgColor"=> "#ffffff",
                    "borderAlpha"=> "20",
                    "showCanvasBorder"=> "0",
                    "usePlotGradientColor"=> "0",
                    "plotBorderAlpha"=> "10",
                    "legendBorderAlpha"=> "0",
                    "legendShadow"=> "0",
                    "legendBgAlpha"=> "0",
                    "valueFontColor"=> "#ffffff",
                    "showXAxisLine"=> "1",
                    "xAxisLineColor"=> "#999999",
                    "divlineColor"=> "#999999",
                    "divLineIsDashed"=> "1",
                    "showAlternateHGridColor"=> "0",
                    "showHoverEffect"=> "1"
                ),
                'categories' => array(0 => array('category'=> array())),
                'dataset' => array(
                    0 => array(
                        'seriesname' => $translator->trans('Net Speed'),
                        'data' => array()
                    ),
                    1 => array(
                        'seriesname' => $translator->trans('Gross Speed'),
                        'data' => array()
                    ),
                    2 => array(
                        'seriesname' => $translator->trans('Accuracy'),
                        "renderAs" => "line",
                        "parentYAxis" => "S",
                        "showValues" => "0",
                        'data' => array()
                    )
                )
            );

            $raor = 0;
            foreach ($status as $item) {
                if($locale == 'mn'){
                    $result['categories'][0]['category'][$raor] = array('label'=> $item['mnTitle']);
                } else {
                    $result['categories'][0]['category'][$raor] = array('label'=> $item['enTitle']);
                }

                $raor++;
            }

            $raor = 0;
            foreach($status as $item) {
                $result['dataset'][0]['data'][$raor] = array(
                    'value' => $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit']), // netspeed
                    'tooltext' => ($locale == 'mn' ? $item['mnTitle'].' ('. $item['cmnTitle'] : $item['enTitle'].' ('. $item['cenTitle']) .'){br}
    '.$translator->trans('Gross Speed').': ' . $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], 0) . ' '.$translator->trans($usr->getMeasureSpeed()).'{br}
    '.$translator->trans('Net Speed').': ' . $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit']) . ' '.$translator->trans($usr->getMeasureSpeed()).'{br}
    '.$translator->trans('Accuracy').': ' . $item['acc'] . '%{br}
    '.$translator->trans('Progress').': ' . round($item['ce']/$item['ae']*100) . '%{br}
    '.$translator->trans('Time Spent').': ' . round($item['time'] / 60) . ' '.$translator->trans('minutes') );
                $raor++;
            }

            $raor = 0;
            foreach($status as $item) {
                $result['dataset'][1]['data'][$raor] = array(
                    'value' => ($this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], 0) - $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit'])),
                    'tooltext' => $translator->trans('Gross Speed').': '.$this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], 0).' '.$translator->trans($usr->getMeasureSpeed()).'');
                $raor++;
            }

            $raor = 0;
            foreach($status as $item) {
                $result['dataset'][2]['data'][$raor] = array(
                    'value' => $item['acc'],
                    'tooltext' => $translator->trans('Accuracy').': '.$item['acc'].'%');
                $raor++;
            }
        }

        if ($type == 'ACTIVITY'){
            $status = $em->getRepository('App:Statustyping')->findActivityGraph($usr->getId(), $lang);
            $result = array(
                'chart' => array(
                    "yAxisName"=> $translator->trans('Minutes Spent Typing'),
                    "numberSuffix"=> ' '.$translator->trans('minutes'),
                    "paletteColors"=> "#25C6D0",
                    "valueFontColor"=> "#ffffff",
                    "baseFont"=> "Arial",
                    "subcaptionFontBold"=> "0",
                    "placeValuesInside"=> "2",
                    "rotateValues"=> "0",
                    "showShadow"=> "0",
                    "divlineColor"=> "#333333",
                    "divLineIsDashed"=> "1",
                    "divlineThickness"=> "1",
                    "divLineDashLen"=> "1",
                    "divLineGapLen"=> "1",
                    "canvasBgColor"=> "#ffffff"
                ),
                'data' => array(
                    0 => array(
                        'label' => $translator->trans('Today'),
                        'value' => '4',
                        'tooltext' => 'Today, 4 minutes'
                    )
                )
            );

            $raor = 0;
            foreach ($status as $item) {
                $result['data'][$raor] = array(
                    'label' => $translator->trans( $this->frmtDateAgo($item['createdAt']) ),
                    'value' => round(($item['spenttime'] / 60)),
                    'tooltext' => $translator->trans( $this->frmtDateAgo($item['createdAt']) ) .', '.round(($item['spenttime'] / 60)).' '.$translator->trans('minutes')
                );
                $raor++;
            }
        }

        if( $type == 'PROBLEM_KEYS' ) {
            $status = $em->getRepository('App:Statustyping')->findProblemKeyGraph($usr->getId(), $lang);

            $charsstatus = array();
            $delchars = array();

            $allKeys = array();
            foreach ($status as $item) {
                $a1 = json_decode( $item['allKeys'], true );
                $allKeys = array_merge_recursive( $allKeys, $a1 );
            }

            foreach($allKeys as $item => $values){
                $i = 0;
                if (is_array($values) || is_object($values))
                {
                    foreach($values as $hits){
                        $i += $hits;
                    }
                } else {
                    $i = $values;
                }
                $charsstatus[str_replace("key-","",$item)] = $i;
            }

            $errorKeys = array();
            foreach ($status as $item) {
                $a1 = json_decode( $item['errorKeys'], true );
                $errorKeys = array_merge_recursive( $errorKeys, $a1 );
            }

            foreach($errorKeys as $item => $values){
                $i = 0;
                if (is_array($values) || is_object($values))
                {
                    foreach($values as $hits){
                        $i += $hits;
                    }
                } else {
                    $i = $values;
                }
                $charsstatus[str_replace("key-","",$item)] = (int) round( ($i*100) / $charsstatus[str_replace("key-","",$item)] );
                $delchars[str_replace("key-","",$item)] = $i;
            }

            foreach(array_diff($charsstatus, $delchars) as $item => $values){
                unset($charsstatus[$item]);
            }

            $data = array();
            foreach($charsstatus as $item => $values){
                array_push($data, array("value" => $values.'', "label" => strtoupper($item), "color" => "ff1a1a"));
            }

            $result = array(
                'chart' => array(
                    "xAxisNamePadding"=> "0",
                    "numberSuffix"=> "%",
                    "showBorder"=> "0",
                    "canvasBorderAlpha"=> "40",
                    "canvasBorderThickness"=> "1",
                    "borderAlpha"=> "0",
                    "chartLeftMargin"=> "0",
                    "chartTopMargin"=> "5",
                    "chartBottomMargin"=> "2",
                    "chartRightMargin"=> "5",
                    "bgColor"=> "#ffffff"
                ),
                'data' => array(
                )
            );

            $result['data'] = $data;
        }

        if($type == 'TYPING_TEST'){
            $status = $em->getRepository('App:Statustyping')->findTypingTestGraph($usr->getId(), $lang);

            $result = array(
                'chart' => array(
                    "xAxisname"=> $translator->trans('Lessons'),
                    "pYAxisName"=> $translator->trans('Speed ('.$usr->getMeasureSpeed().')'),
                    "sYAxisName"=> $translator->trans('Accuracy').' %',
                    "numberSuffix"=> ' '.$translator->trans($usr->getMeasureSpeed()),
                    "sNumberSuffix"=> "%",
                    "sYAxisMaxValue"=> "100",
                    "sYAxisMinValue"=> "0",
                    "paletteColors"=> "#25C6D0,#25d085,#25d02f",
                    "bgColor"=> "#ffffff",
                    "borderAlpha"=> "20",
                    "showCanvasBorder"=> "0",
                    "usePlotGradientColor"=> "0",
                    "plotBorderAlpha"=> "10",
                    "legendBorderAlpha"=> "0",
                    "legendShadow"=> "0",
                    "legendBgAlpha"=> "0",
                    "valueFontColor"=> "#ffffff",
                    "showXAxisLine"=> "1",
                    "xAxisLineColor"=> "#999999",
                    "divlineColor"=> "#999999",
                    "divLineIsDashed"=> "1",
                    "showAlternateHGridColor"=> "0",
                    "showHoverEffect"=> "1"
                ),
                'categories' => array(0 => array('category'=> array())),
                'dataset' => array(
                    0 => array(
                        'seriesname' => $translator->trans('Net Speed'),
                        'data' => array()
                    ),
                    1 => array(
                        'seriesname' => $translator->trans('Gross Speed'),
                        'data' => array()
                    ),
                    2 => array(
                        'seriesname' => $translator->trans('Accuracy'),
                        "renderAs" => "line",
                        "parentYAxis" => "S",
                        "showValues" => "0",
                        'data' => array()
                    )
                )
            );

            $raor = 0;
            if(!$status){
                $result['dataset'] = array();
            }
            foreach ($status as $item) {
                $result['categories'][0]['category'][$raor] = array('label'=> $item['createdAt']->format('F j, Y'));
                $raor++;
            }

            $raor = 0;
            foreach($status as $item) {
                $result['dataset'][0]['data'][$raor] = array(
                    'value' => $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit']),
                    'tooltext' => $translator->trans('Net Speed').': ' . $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit']) . ' '.$translator->trans($usr->getMeasureSpeed()).' ' );
                $raor++;
            }

            $raor = 0;
            foreach($status as $item) {
                $result['dataset'][1]['data'][$raor] = array(
                    'value' => ($this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], 0) - $this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], $item['mistakehit'])),
                    'tooltext' => $translator->trans('Gross Speed').': '.$this->calculateSpeed($usr->getMeasureSpeed(), $item['correcthit'], $item['time'], 0).' '.$translator->trans($usr->getMeasureSpeed()).' ');
                $raor++;
            }

            $raor = 0;
            foreach($status as $item) {
                $result['dataset'][2]['data'][$raor] = array(
                    'value' => $item['acc'],
                    'tooltext' => $translator->trans('Accuracy').': '.$item['acc'].'%');
                $raor++;
            }
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'text/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/create/import/valid/", name="usercreateimportvalidpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usercreateimportvalid(Request $request,TranslatorInterface $translator, $_locale)
    {
        $i = 0;
        $data =array();
        $error = false;
        $msg = "";

        $a_entity = new ImportStudent();
        $a_form = $this->createForm(StudentImport::class, $a_entity);
        if('POST' === $request->getMethod()) {
            if ($request->request->has('student_import')) {
                $a_form->handleRequest($request);
                if ($a_form->isSubmitted() && $a_form->isValid()) {

                    $file = $a_form->get('file');

                    $ALMOST_DONE = $translator->trans('Almost Done!');
                    $VALIDATION_ERROR = $translator->trans('Validation Error!');
                    //$ALMOST_DONE = 'Some problems were found with your import file.';
                    //$ALMOST_DONE = 'Almost Done!';
                    //$ALMOST_DONE = 'Almost Done!';
                    //$ALMOST_DONE = 'Almost Done!';


                    if (($handle = fopen($file->getData()->getRealPath(), "r")) !== FALSE) {
                        while(($row = fgetcsv($handle)) !== FALSE) {
                            if($i == 0){
                                $i = 1;
                            } else {
                                $key['recid']    = $i;
                                $isadded = $this->getDoctrine()
                                    ->getRepository('App:Student')
                                    ->findAccount($row[0]);

                                if( isset($isadded)) {
                                    $key['status']    = '<i class="fa fa-minus-circle fa-lg text-error" aria-hidden="true" title="The username already exists in Bicheech.com. Bicheech.com uses a shared set of usernames between all accounts. If necessary, we suggest prefixing usernames with something unique such as a school name\'s acroynm, for example ucla.'.$row[0].'"></i>';
                                    $key['username']  = '<div class="text-error">'.$row[0].'</div>';
                                    $error = true;
                                } else {
                                    $key['status']    = '<i class="fa fa-check fa-lg text-success" aria-hidden="true"></i>';
                                    $key['username']    = ''.$row[0].'';
                                }
                                $key['password']  = $row[1];
                                $key['firstname'] = $row[2];
                                $key['lastname']  = $row[3];
                                $key['email']     = $row[4];

                                array_push($data, $key);
                                $i++;
                            }
                        }
                    }

                    if($error == true) {
                        $msg = '<div class="msg-error"><strong>' . $VALIDATION_ERROR . '</strong>';

                        $url = $this->generateUrl(
                            'usercreateimportpage'
                        );

                        if($_locale =='mn') {
                            $msg = $msg . ' Таны оруулсан файл дээр зарим асуудлууд олдсон байна. <br>Дэлгэрэнгүй мэдээлэл авахын тулд хулганы заагчийг <i class="fa fa-minus-circle fa-lg text-error" aria-hidden="true"></i> дүрсэн дээр дар.<br><br> Импортлох файлаа засаад, хүснэгтний доод талд <a href="'. $url .'">дахиж оролдох</a> товчоор оруулна уу.</div>';
                        } else {
                            $msg = $msg . ' Some problems were found with your import file. <br>Click or hold your mouse pointer over the <i class="fa fa-minus-circle fa-lg text-error" aria-hidden="true"></i> icons for more information.<br><br>Please correct your import file and <a href="'. $url .'">try again</a> button at the bottom of the table.</div>';
                        }

                    } else {
                        $msg = '<div class="msg-info"><strong>' . $ALMOST_DONE . ' </strong>';
                        if($_locale =='mn') {
                            $msg = $msg .'Өөрийн мэдээллээ хянаж, <strong>"Импортоо Дуусгах"</strong> дээр дарна.</div>';
                        } else {
                            $msg = $msg .'Review your information and click the <strong>"Complete Import"</strong></div>';
                        }
                    }
                }
            }
        }
        $response = new Response();
        $response->setContent(json_encode(array(
            'data' => $data,
            'msg' => $msg,
            'error' => $error,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/export/gid/{clss}", name="usersexportpage", methods={"GET"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersexport(Request $request, $clss)
    {
        if($clss == 'all') {
            $results = $this->getDoctrine()
                ->getRepository('App:GradeStudent')
                ->findStudentClass($this->getUser());
        } else {
            $results = $this->getDoctrine()
                ->getRepository('App:GradeStudent')
                ->findStudentClass($this->getUser(), $clss);
        }

        $columns = [
            'username'      => ['username', 'Username'],
            'class'         => ['class', 'Class'],
            'firstname'     => ['firstname', 'Firstname'],
            'lastname'      => ['lastname', 'Lastname'],
            'email'         => ['email', 'Email'],
            'lastLogin'     => ['lastLogin', 'Last Login'],
            'signupDate'    => ['signupDate', 'Date Enrolled'],
            'userType'      => ['userType', 'User Type']
        ];

        $response = new StreamedResponse();
        $response->setCallback(function() use ($results, $columns) {
            $handle = fopen('php://output', 'w');

            $data = array(
                'Users  ['. count($results) .' Records]',
            );
            fputcsv($handle, $data, ',');
            fputcsv($handle, array_column($columns, 1), ',');

            foreach ($results as $row) {
                $fileRow = [];
                foreach ($columns as $values) {
                    if($values[0] == 'lastLogin'){
                        $fileRow[] = $row[$values[0]]->format('Y-m-d H:i:s');
                    }
                    if($values[0] == 'signupDate') {
                        $fileRow[] = $row[$values[0]]->format('Y-m-d H:i:s');
                    }
                    if($values[0] != 'lastLogin' && $values[0] != 'signupDate'){
                        $fileRow[] = $row[$values[0]];
                    }
                }
                fputcsv($handle, $fileRow, ',');
            }

            fclose($handle);
        }
        );

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="users.csv"');

        $response->send();

        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/unlink/", name="usersunlinkpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersunlink(Request $request)
    {
        $users = json_decode($request->request->get('users'), true);

        foreach ($users as $key) {
            $student = $this->getDoctrine()
                ->getRepository('App:Student')->findOneById($key);

            $student->setGrade(null);

            $unlink = $this->getDoctrine()
                ->getRepository('App:GradeStudent')->findOneBy(array('student' => $student ));

            $em = $this->getDoctrine()->getManager();

            $em->persist($student);
            $em->flush();

            $em->remove($unlink);
            $em->flush();
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => true,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/reset/", name="usersresetpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersreset(Request $request)
    {
        $users = json_decode($request->request->get('users'), true);

        foreach ($users as $key) {
            $student = $this->getDoctrine()
                ->getRepository('App:Student')->findOneById($key);

            $pr = $this->getDoctrine()
                ->getRepository('App:PasswordReset')->findBy(array('student'=> $student));

            foreach ($pr as $key) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($key);
                $em->flush();
            }

            $st = $this->getDoctrine()
                ->getRepository('App:Statustyping')->findBy(array('student'=> $student));

            foreach ($st as $key) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($key);
                $em->flush();
            }

            $uu = $this->getDoctrine()
                ->getRepository('App:UpgradedUsers')->findBy(array('student'=> $student));

            foreach ($uu as $key) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($key);
                $em->flush();
            }
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => true,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/password/", name="userspasswordpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function userspassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $users = json_decode($request->request->get('users'), true);
        $password = $request->request->get('password');

        foreach ($users as $key) {
            $student = $this->getDoctrine()
                ->getRepository('App:Student')->findOneById($key);

            $encoded = $passwordEncoder->encodePassword($student, $password);
            $student->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => true,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/delete/", name="usersdeletepage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersdelete(Request $request)
    {
        $users = json_decode($request->request->get('users'), true);

        foreach ($users as $key) {
            $student = $this->getDoctrine()
                ->getRepository('App:Student')->findOneById($key);



            $gs = $this->getDoctrine()
                ->getRepository('App:GradeStudent')->findBy(['student'=> $student, 'teacher' => $this->getUser()]);

            foreach ($gs as $item1) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($item1);
                $em->flush();
            }

            $pr = $this->getDoctrine()
                ->getRepository('App:PasswordReset')->findBy(['student'=> $student]);

            foreach ($pr as $item2) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($item2);
                $em->flush();
            }

            $st = $this->getDoctrine()
                ->getRepository('App:Statustyping')->findBy(['student'=> $student]);

            foreach ($st as $item3) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($item3);
                $em->flush();
            }

            $uu = $this->getDoctrine()
                ->getRepository('App:UpgradedUsers')->findBy(['student'=> $student]);

            foreach ($uu as $item4) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($item4);
                $em->flush();
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($student);
            $em->flush();
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => true,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/move/", name="usermovepage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usermove(Request $request)
    {
        $success = false;
        // get id of users.
        $users = json_decode($request->request->get('users'), true);
        // get class id.
        $class_id = $request->request->get('groupID');

        foreach ($users as $key) {
            $student = $this->getDoctrine()
                ->getRepository('App:Student')->findOneById($key);

            $gs = $this->getDoctrine()
                ->getRepository('App:GradeStudent')
                ->findStudent( $this->getUser(), $student);
            if($class_id == 'ungrouped'){
                $gs->setGrade(null);

                $em = $this->getDoctrine()->getManager();
                $em->persist($gs);
                $em->flush();

                $success = true;
            }
            else {
                $g = $this->getDoctrine()
                    ->getRepository('App:Grade')
                    ->findOneById($class_id);

                $gs->setGrade($g);

                $em = $this->getDoctrine()->getManager();
                $em->persist($gs);
                $em->flush();

                $student->setGrade($g);

                $em = $this->getDoctrine()->getManager();
                $em->persist($student);
                $em->flush();

                $success = true;
            }
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => $success ,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/grid/", name="usersjsonpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersjson(Request $request,TranslatorInterface $translator)
    {
        $data =array();
        $gaGroupID  = $request->request->get('gaGroupID');
        $students = $this->getDoctrine()
            ->getRepository('App:GradeStudent')
            ->findStudentClass($this->getUser(), $gaGroupID);

        foreach ($students as $key) {
            $key['userID'] = $key['userID'].'';
            $key['class'] = $translator->trans($key['class']);
            $key['email'] = $key['email'] == null? '': $key['email'];
            $key['lastLogin'] = $key['lastLogin']->format('Y-m-d H:i:s');
            $key['signupDate'] = $key['signupDate']->format('Y-m-d H:i:s');
            $key['userType'] = $translator->trans($key['userType']);
            array_push($data, $key);
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'results' => $data,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/load", name="usersloadpage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersload(Request $request)
    {
        $id = $request->request->get('userID');

        $gs = $this->getDoctrine()
            ->getRepository('App:GradeStudent')
            ->findStudent($this->getUser(), $id);

        $data = array();

        $success = false;
        if(isset($gs)) {
            $data['userID'] = $gs->getStudent()->getId();
            $data['gaLicenseID'] = '';
            $data['username'] = $gs->getStudent()->getUsername();
            $data['password'] = '';
            $data['firstname'] = $gs->getStudent()->getFirstname();
            $data['lastname'] = $gs->getStudent()->getLastname();
            $data['email'] = $gs->getStudent()->getEmail();
            $data['spaces'] = 1;
            $data['speed'] = $gs->getStudent()->getMeasureSpeed();
            $data['sounds'] = $gs->getStudent()->getEnableSounds();

            $success = true;
        }

        //var_dump($success);die();
        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => $success ,
            'data' => $data,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("{_locale}/teacher/users/save/", name="usersavepage", methods={"POST"}, defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Security("is_granted('ROLE_TEACHER')")
     */
    public function usersave(Request $request)
    {
        $success = false;

        $student = $this->getDoctrine()
            ->getRepository('App:Student')
            ->findOneById($request->request->get('userID'));

        $student->setUsername($request->request->get('username'));
        if( $request->request->get('password') != '' ) {
            $student->setPassword($request->request->get('password'));
        }
        $student->setFirstname($request->request->get('firstname'));
        $student->setLastname($request->request->get('lastname'));
        $student->setEmail($request->request->get('email'));
        $student->setMeasureSpeed($request->request->get('speed'));
        //$student->setSpaces(1); #2
        $student->setEnableSounds($request->request->get('sounds'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($student);
        $em->flush();

        $success = true;

        $response = new Response();
        $response->setContent(json_encode(array(
            'success' => $success ,
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
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

    public function frmtDateAgo($value)
    {
        $today = new \DateTime();
        $yesterday = new \DateTime();
        $yesterday->modify('-1 day');

        $value = date_create($value);

        if ($value->format('Y-m-d') == $today->format('Y-m-d'))
        {
            return 'Today';
        }
        elseif ($value->format('Y-m-d') == $yesterday->format('Y-m-d'))
        {
            return 'Yesterday';
        }
        else
        {
            return $value->format('F j, Y');
        }
    }

}