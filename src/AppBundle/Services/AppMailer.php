<?php
// src/AppBundle/Services/AppMailer.php

namespace AppBundle\Services;

use AppBundle\Entity\Messages;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class AppMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $swiftmailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(\Swift_Mailer $swiftmailer, EngineInterface $templating)
    {
        $this->swiftmailer = $swiftmailer;
        $this->templating  = $templating;
    }

    public function sendContactEmail(Messages $message)
    {
        // Envoie un remerciement à l'utilisateur
        $email_contact = \Swift_Message::newInstance()
            ->setSubject('Votre message a bien été reçu !')
            ->setFrom('vleprince123@gmail.com')
            ->setTo($message->getEmail())
            ->setContentType('text/html')
            ->setBody(
                $this->templating->render(
                    'default/email.html.twig',
                    array(
                        'nom'    => $message->getNom(),
                        'prenom' => $message->getPrenom(),
                        'email'  => $message->getEmail(),
                    )
                ));

        $this->swiftmailer->send($email_contact);


        // Envoie un message à Gustavo pour le prévenir qu'il vient d'être contacté
        $email_contact = \Swift_Message::newInstance()
            ->setSubject('Gustavo, vous avez reçu un message !')
            ->setTo('vleprince123@gmail.com')
            ->setFrom($message->getEmail())
            ->setContentType('text/html')
            ->setBody(
                $this->templating->render(
                    'default/email_contact_gustavo.html.twig',
                    array(
                        'message' => $message,
                    )
                ));

        $this->swiftmailer->send($email_contact);
    }

}
