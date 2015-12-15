<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Messages;
use AppBundle\Entity\Page;
use AppBundle\Entity\Post;
use AppBundle\Form\MessagesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="accueil")
     */
    public function accueilAction()
    {
        $em   = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findOneBy(array(), array(
            'date' => 'desc'
        ));

        $em       = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('AppBundle:Comment')->findBy(array('post' => $post),
            array('date' => 'desc'),
            3,
            0
        );

        return $this->render("default/accueil.html.twig", array(
            'post'     => $post,
            'comments' => $comments
        ));
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $messages = new Messages();
        $form     = $this->get('form.factory')->create(new MessagesType(), $messages);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($messages);
            $em->flush();

            $this->get('app_mailer')->sendContactEmail($messages);

            $request->getSession()->getFlashBag()->add('notice', 'La question a bien été envoyé.');

            return $this->redirect($this->generateUrl('accueil', array('id' => $messages->getId())));
        } else {
            return $this->render('default/contact.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * @Route("/page/{slug}", name="page")
     * @param $slug
     *
     * @return Response
     */
    public function pageAction($slug)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Page');


        $page = $repository->findOneBy(array('slug' => $slug));


        if ($page === null) {
            throw new AccessDeniedException('La page '.$slug.' n\'existe pas.');
        }

        return $this->render('default/page.html.twig', array(
            'slug' => $slug,
        ));
    }


}
