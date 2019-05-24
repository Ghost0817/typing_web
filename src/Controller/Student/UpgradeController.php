<?php

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UpgradedUsersType;
use App\Entity\UpgradedUsers;

class UpgradeController extends AbstractController
{
    /**
     * @Route("{_locale}/student/upgrade/", name="tutor_upgrade", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('App:Mature')->findAll();
        #dump('as');die();
        // replace this example code with whatever you need
        return $this->render('upgrade/index.html.twig', array(
          'entities' => $entities,
          "menu"=>"upgrade",
        ));
    }

    /**
     * @Route("{_locale}/student/upgrade/payment/plan/{id}", name="tutor_upgrade_payment", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Template()
     */
    public function payment(Request $request,$id)
    {
        if($this->getUser())
        {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('App:Mature')->findOneById($id);
            $uu  = new UpgradedUsers();
            $form = $this->createForm(UpgradedUsersType::class, $uu);
            # start generate invoice number
            list($msec, $sec)=explode(' ',microtime());
            $datestr = date('Ymdhis', $sec).sprintf("%06d",$msec);
            $serialno = substr(base_convert($datestr, 10, 16),0,12);
            # end generate invoice number

            if ($request->isMethod('POST')) {

                $form->handleRequest($request);

                $dt = new \DateTime('now');
                $uu->setTranDate(new \DateTime('now'));
                $uu->setExpDate($dt->modify('+1 year'));
                $uu->setIpAddress($request->getClientIp());
                $uu->setIsPaid(0);
                $uu->setIsSend(0);
                $uu->setInvoiceNumber($serialno);
                $uu->setStudent($this->getUser());
                $uu->setUpgrade($entity);
                if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($uu);
                    $em->flush();

                    return $this->redirect($this->generateUrl('tutor_invoice',array('id' => $uu->getInvoiceNumber())));
                }

            }
            return $this->render('upgrade/payment.html.twig', array(
              'entity' => $entity,
              'form' => $form->createView(),
              "menu"=>"upgrade",
              "invoiceNum" => $serialno
            ));

        }
        else
        {
            return $this->redirect($this->generateUrl('tutor_login'));
        }
    }

    /**
     * @Route("{_locale}/student/invoice/{id}", name="tutor_invoice", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     * @Template()
     */
    public function invoice(Request $request,$id, \Swift_Mailer $mailer)
    {
        if($this->getUser())
        {
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('App:UpgradedUsers')->findOneBy(array( 'invoiceNumber' => $id, 'student' => $this->getUser() ));
            if ($entities == null)
            {
                return $this->redirect($this->generateUrl('tutor_upgrade'));
            }
            $mature = $em->getRepository('App:Mature')->findOneById($entities->getUpgrade()->getId());
            if(!$entities->getIsSend())
            {
                // Энэ хэсгийн "tutor_upgrade_payment" рүү пост хийхэд амжилттай болох үед энэ чидэгдэнэ.
                $message =(new \Swift_Message('Төлбөрийн нэхэмжлэх, Customer Invoice: '.$entities->getInvoiceNumber() ))     // we create a new instance of the Swift_Message class
                ->setFrom('info@typingweb.mn')     // we configure the sender
                ->setTo($this->getUser()->getEmail())     // we configure the recipient
                ->setBody($this->renderView('Tempmail/invoice.html.twig',
                    array(
                        'entities' => $entities,
                        'mature' => $mature
                    )
                ), 'text/html')
                    // and we pass the $name variable to the text template which serves as a body of the message
                ;
                $mailer->send($message);     // then we send the message.
                $em->persist($entities->setIsSend(1));
                $em->flush();
            }

            return $this->render('upgrade/invoice.html.twig', array(
                  'entities' => $entities,
                  'mature' => $mature
            ));
        }
        else
        {
            return $this->redirect($this->generateUrl('tutor_login'));
        }
    }
}
