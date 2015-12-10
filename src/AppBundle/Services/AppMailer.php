<?php
// src/AppBundle/Services/AppMailer.php

namespace AppBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use AppBundle\Entity\Messages;


class AppMailer
{
  private $mailer;
  private $message;
  private $templating;
  
  public function __construct(\Swift_Mailer $mailer, $templating)
  {
    $this->mailer = $mailer;
	$this->templating = $templating;
  }  

  /**
   * @Route("/", name="email_contact")
   */
   public function sendContactEmail(Message $message)
   {
	$email_contact = \Swift_Message::newInstance()
			->setSubject('Votre message a bien été reçu !')
			->setFrom('vleprince123@gmail.com')
			->setTo($email())
			->setBody(
				$this->templating->render(
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