<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;


class BlogController extends Controller
{

    /**
     * @Route("/blog", name="blog")
     * @param Request $request
     *
     * @return Response
     */
    public function blogAction(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Post');

        $orderBy = $request->query->get('orderBy');
        $direction = $request->query->get('direction');
        $auteurs = $request->query->get('auteurs');

        $posts = $repository->myFindAll($orderBy, $direction, $auteurs);

        $auteursListe = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        return $this->render("blog/blog.html.twig", array(
            'posts' => $posts,
            'auteurs' => $auteursListe,
        ));
    }

    /**
     * @Route("/article/{id}", name="article")
     * @param $id
     *
     * @return Response
     */
    public function articleAction($id)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Post');

        $post = $repository->find($id);

        $em = $this->getDoctrine()->getManager();
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
	 * 
     * @Route("/commenter/{id}", name="commenter")
     * @param mixed   $id
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function commenterAction($id, Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Post');

        /** @var Post $post */
        $post = $repository->find($id);

        $comment->setPost($post);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Le commentaire a été bien ajouté !');

            return $this->redirect($this->generateUrl('article', array('id' => $post->getId())));
        } else {
            return $this->render('blog/commenter.html.twig', array(
                'form' => $form->createView(),
                'post' => $post,
            ));
        }
    }

}
