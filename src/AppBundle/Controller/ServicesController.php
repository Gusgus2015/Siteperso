<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Entity\Page;
use AppBundle\Form\PageType;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use FOS\UserBundle\Mailer\Mailer;

class ServicesController extends Controller
{
	/**
     * @Route("/", name="email_contact")
     */
	public function indexAction($nom, $prenom, $email)
	{
		$email = \Swift_Message::newInstance()
			->setSubject('Votre message a bien été reçu !')
			->setFrom('vleprince123@gmail.com')
			->setTo($email)
			->setBody(
				$this->renderView(
					// app/Resources/views/default/email.html.twig
					'default/email.html.twig',
					array(
						'nom' => $nom, 
						'prenom' => $prenom,
						'email' => $email)
					),
					'Merci de nous contacter, nous sommes ravis de vous donner une réponse le plus vite possible.'
					);
					
		$this->get('mailer')->send($email);

		return $this->redirect($this->generateUrl('accueil'));
	}
}