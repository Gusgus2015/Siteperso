<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PageRepository")
 */
class Page
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;
	
	/**
     * @var user
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, name="auteur_id")
     */
    protected $auteur;
	
	/**
     * Get titre
     *
     * @return  Page
     */
	public function __construct()
	{	
		
	}
	
	
	public function __toString() 
	{		
		return $this->titre.', par '.$this->auteur;
	}
	
	
	
	/**
	 * Update slug
	 *
     * @return slug
     */
	public function updateSlug() 
	{
     $this->slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $this->titre);
	}
	
	
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Page
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Page
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
	
	 /**
     * Set auteur
     *
     * @param User $auteur
     *
     * @return Page
     */
    public function setAuteur(User $auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return User
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
}
