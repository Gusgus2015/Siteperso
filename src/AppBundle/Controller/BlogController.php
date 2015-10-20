<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Form\MessagesType;
use Symfony\Component\Form\Form;

class BlogController extends Controller
{
     /**
     * @Route("/voir", name="voir")
     */
	public function voirAction()
	{
		return $this->render("blog/voir.html.twig");
	}
	
	/**
     * @Route("/ajouter", name="ajouter")
     */
	public function ajouterAction()
	{
		return $this->render("blog/ajouter.html.twig");
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