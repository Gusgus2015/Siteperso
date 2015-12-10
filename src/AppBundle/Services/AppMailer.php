<?php
// src/AppBundle/Services/AppMailer.php

namespace AppBundle\Services;

class AppMailer
{
  private $mailer;
  private $message;
  
  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }  

  /**
   * @Route("/", name="email_contact")
   */
   public function sendContactEmail(Message $message)
   {
	 $message = \Swift_Messages::newInstance()
			->setSubject('Votre message a bien été reçu !')
			->setFrom('vleprince123@gmail.com')
			->setTo($email())
			->setBody(
				$this->renderView(
					// app/Resources/views/default/email.html.twig
					'default/email.html.twig',
					array(
						'nom' => $nom, 
						'prenom' => $prenom,
						'email' => $email,
					),
					'Merci de nous contacter, nous sommes ravis de vous donner une réponse le plus vite possible.'
					));
   }
 
}