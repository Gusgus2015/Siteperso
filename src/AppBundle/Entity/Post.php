<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PostRepository")
 */
class Post
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var user
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, name="auteur_id")
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="post", cascade={"remove"})
     *
     */
    protected $comments;
	
	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="posts", cascade={"persist"})
	 */
	protected $tags;

	 /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Page", mappedBy="auteur", cascade={"remove"})
     *
     */
    protected $pages;
	
	// Comme la propriété $comments doit être un ArrayCollection,
	// On doit la définir dans un constructeur :
	public function __construct()
	{
		$this->date = new \Datetime();
		$this->comments = new ArrayCollection();
	}
	
	
	
	public function __toString() 
	{
		return $this->titre.', par '.$this->auteur;
	}

    /**
     * @param Comment $comment
     *
     * @return $this
     */
	public function addComment(Comment $comment)
	{
		$this->comments[] = $comment;

		$comment->setPost($this);

		return $this;
	}

	public function removeComment(Comment $comment)
	{
		$this->comments->removeElement($comment);
	}

	public function getComment()
	{
		return $this->comments;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Post
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
     * Set auteur
     *
     * @param User $auteur
     *
     * @return Post
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

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Post
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
     * Get comments
     *
     * @return Comment[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add tag
     *
     * @param \AppBundle\Entity\Tag $tag
     *
     * @return Post
     */
    public function addTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \AppBundle\Entity\Tag $tag
     */
    public function removeTag(\AppBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
