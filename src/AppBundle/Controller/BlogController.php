<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;
use Symfony\Component\Form\Form;

class BlogController extends Controller
{

    /**
     * @Route("/blog", name="blog")
     */
    public function blogAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Post');

        $posts = $repository->myFindAll();

        return $this->render("blog/blog.html.twig", array(
            'posts' => $posts
        ));
    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function articleAction($id)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Post');

        $post = $repository->find($id);

        $em       = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('AppBundle:Comment')->findBy(array('post' => $post),
            array('date' => 'desc'),
            3,
            0
        );

        return $this->render("blog/article.html.twig", array(
            'post'     => $post,
            'comments' => $comments
        ));
    }

    /**
     * @Route("/ajouter", name="ajouter")
     */
    public function ajouterAction(Request $request) //la methode est mauvais, ne marche pas.
    {
        $comment = new Comment();
        $form    = $this->get('form.factory')->create(new CommentType(), $comment);


        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'La question a bien été envoyé.');

            return $this->redirect($this->generateUrl('article', array('id' => $comment->getId())));
        } else {
            return $this->render('blog/ajouter.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }


    /**
     * @Route("/modifier", name="modifier")
     */
    public function modifierAction()
    {
        return $this->render("blog/modifier.html.twig");
    }

    /**
     * @Route("/supprimer", name="supprimer")
     */
    public function supprimerAction()
    {
        return $this->render("blog/supprimer.html.twig");
    }

}
