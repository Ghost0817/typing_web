<?php

namespace App\Controller\Entertainment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormError;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;

class ContactusController extends AbstractController
{
    /**
     * @Route("{_locale}/contact-us/", name="contactus_index", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     *
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Contact();
        $form = $this->createForm(ContactType::class, $entity);
        $session  = $this->get("session");
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isValid()) {

                    $entity->setCreated(new \DateTime('now'));
                    $entity->setReaded(0);
                    $em->persist($entity);
                    $em->flush();

                    $this->addFlash(
                        'success',
                        'Таны саналыг хүлээж авлаа. Холбогдох хүмүүс нь удахгүй холбоо барина.'
                    );

                    return $this->redirect($this->generateUrl('contactus_index'));
            }
        }

        $faqs = $em->getRepository('App:Faqs')->findAll();

        return $this->render('contactus/index.html.twig', array(
            'entity'   => $entity,
            'form'     => $form->createView(),
            'faqs'     => $faqs,
            'menu'     => 'contactus'
            )
        );
    }
}
