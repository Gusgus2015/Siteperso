<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Form\MessagesType;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="accueil")
     */
    public function accueilAction()
    {
         $em = $this->getDoctrine()->getManager();
		 $post = $em->getRepository('AppBundle:Post')->findAll();
		 
		 return $this->render("default/accueil.html.twig", array(
		 'post' => $post,
		 ));
    }
	 /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
         $em = $this->getDoctrine()->getManager();
		 
		 $form = $this->createForm(new MessagesType());

		 
		 return $this->render('default/contact.html.twig', array(
		 'form' => $form->createView(),
		 ));
    }
	
	
    
}
