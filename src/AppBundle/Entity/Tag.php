<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tag
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

	/**
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Post", mappedBy="tags")
	 */
	private $posts;

	/**
	 * Méthode magique pour représenter l'objet sous forme d'une chaîne de caractères
	 */
	public function __toString() {
		return $this->id.' - '.$this->nom;
	}

	public function __construct() {
		$this->posts = new ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Tag
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

	public function getPosts() {
		return $this->posts;
	}

	public function addPost(Post $post) {
		$this->posts->add($post);
		return $this;
	}

	public function removePost(Post $post) {
		$this->posts->removeElement($post);
		return $this;
	}
}
