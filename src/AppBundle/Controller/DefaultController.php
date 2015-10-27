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

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="accueil")
     */
    public function accueilAction()
    {  
		$em = $this->getDoctrine()->getManager();
		$post = $em->getRepository('AppBundle:Post')->findOneBy(array(), array(
		'date' => 'desc'
		));
		
		//รงa ne marche pas, forcement il y a un erreur encore
		$em = $this->getDoctrine()->getManager();
		$comments = $em->getRepository('AppBundle:Comment')->findBy(array('post' => $post),		 
		array('date' => 'desc'),       
		3, 								
		0		
		);								

		return $this->render("default/accueil.html.twig", array(
		'post' => $post,
		'comments' => $comments
		));		
	}
	
	/**
	 * @Route("/contact", name="contact")
	 */
    public function contactAction(Request $request)
    {	
		$messages = new Messages();
		$form = $this->get('form.factory')->create(new MessagesType(), $messages);
		
		
		if ($form->handleRequest($request)->isValid()) 
		{
			$em = $this->getDoctrine()->getManager();
			$em->persist($messages);
			$em->flush();
			
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
	
	
    
}