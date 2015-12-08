<?php
// src/AppBundle/Service/app_mailer.php

namespace AppBundle\Services;

class App_mailer
{
  private $email;
  private $nom;
  private $prenom;
  
 
  public function __construct(\Swift_Mailer $email, $nom, $prenom)
  {
    $this->email    = $email;
    $this->nom      = $nom;
	$this->prenom   = $prenom;
  }  
}