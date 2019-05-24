<?php

namespace App\Controller\Entertainment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogsController extends AbstractController
{
    /**
     * @Route("{_locale}/blog/", name="blog_index", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     *
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('App:Blog')->findBy(array('isShow' => 1), array('sortnum' => 'DESC'));
        $tags = $em->getRepository('App:Tag')->findBy(array(), array('name' => 'ASC'));
        #dump($blogs[1]);die();
        //dump($blogs[1]->getTags());die();
        return $this->render('blogs/index.html.twig', array(
            'blogs' => $blogs,
            'tags' => $tags,
            "menu" => '5'
        ));
    }

    /**
     * @Route("{_locale}/blog/tag/{tag}/", name="blog_tag", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     *
     */
    public function listtag(Request $request, $tag)
    {
        $em = $this->getDoctrine()->getManager();
        $blogs = $em->getRepository('App:Blog')->findAllSlugOfTag($tag);
        $tags = $em->getRepository('App:Tag')->findBy(array(), array('name' => 'ASC'));
        return $this->render('blogs/listtag.html.twig', array(
            'blogs' => $blogs,
            'mytag' => $tag,
            'tags' => $tags,
            "menu" => '5'
        ));
    }

    /**
     * @Route("{_locale}/blog/read/{slug}/", name="blog_read", defaults={
     *     "_locale": "mn"
     * }, requirements={
     *     "_locale": "mn|en"
     * })
     *
     */
    public function show(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $blog = $em->getRepository('App:Blog')->findOneBySlug($slug);
        $tags = $em->getRepository('App:Tag')->findBy(array(), array('name' => 'ASC'));

        $em->persist($blog);
        $em->flush();
        return $this->render('blogs/show.html.twig', array(
            'blog' => $blog,
            'tags' => $tags,
            "menu" => '5'
        ));
    }
}
