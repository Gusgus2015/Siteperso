<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Messages;
use AppBundle\Form\MessagesType;
use AppBundle\Entity\Page;
use AppBundle\Form\PageType;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;


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

        if ($form->handleRequest($request)->isValid()) 
		{
            $em = $this->getDoctrine()->getManager();
            $em->persist($messages);
            $em->flush();

			$app_mailer = \Swift_Message::newInstance()
				->setSubject('Votre message a bien été reçu !')
				->setFrom(array('vleprince123@gmail.com' => 'Blog Siteperso'))
				->setTo($messages->getEmail())
				->setCharset('utf-8')
				->setContentType('text/html')
				->setBody(
					$this->renderView(
						// app/Resources/views/default/email.html.twig
						'default/email.html.twig'));
						
			$this->get('mailer')->send($app_mailer);
			 
			
            $request->getSession()->getFlashBag()->add('notice', 'La question a bien été envoyé.');

            return $this->redirect($this->generateUrl('accueil', array('id' => $messages->getId())));
        }
		else 
		{
            return $this->render('default/contact.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }
    /**
     * @Route("/page/{slug}", name="page")
	 * @param $slug
	 * @return Response
     */
	public function pageAction($slug)
	{		
		$repository = $this
			->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Page');
			
		
		$page = $repository->findOneBy(array('slug' => $slug));

		
		if ($page === null)
		{
			throw new AccessDeniedException('La page ' .$slug. ' n\'existe pas.');
		}
						
		return $this->render('default/page.html.twig', array(
					'slug' => $slug,
					));		
	}	
	
	
}