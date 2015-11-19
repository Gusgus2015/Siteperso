<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;

class AdminController extends Controller
{
	/**
	 * @Route("/ajouter_post", name="ajouter_post")
	 *
	 * @param Request $request
	 */
    public function ajouterPostAction(Request $request)
    {
       $post = new Post();

	   return $this->handlePostForm($post, $request);
    }

	/**
     * @Route("/modifier_post/{id}", name="modifier_post")
	 *
	 *@param Request $request
	 *
	 *@return \Symfony\Component\HttpFoundation\Response
     */
    public function modifierPostAction($id, Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Post');

        $post = $repository->Find($id);

	    return $this->handlePostForm($post, $request);
    }

	/**
     * @Route("/supprimer_post/{id}", name="supprimer_post")
     */
	public function supprimerPostAction($id, Request $request)
    {
	    $em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$post = $em->getRepository('AppBundle:Post')->find($id);

		if (null === $post) {
		  throw new NotFoundHttpException("Le post N° ".$id." n'existe pas.");
		}

		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression d'annonce contre cette faille
		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($post);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('notice', "Le post a bien été supprimée.");

		  return $this->redirect($this->generateUrl('blog'));
		}

		// Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render('blog/supprimer.html.twig', array(
		  'post' => $post,
		  'form'   => $form->createView()
		));
    }

	/**
     * @Route("/supprimer_comment/{id}", name="supprimer_comment")
     */
	public function supprimerCommentAction($id, Request $request)
    {
	   /**Recupere l'EntityManager $em */
	   $em = $this->getDoctrine()->getManager();
	   /**Recupere le repository */
	   $repository = $em->getRepository('AppBundle:Comment');

	    /**Recupere l'entité qui correspond à l'id */
	   $comment = $repository->find($id);

	   /**On supprime le post */
	   $em->remove($comment);
	   $em->flush();

	   $request->getSession()->getFlashBag()->add('notice', 'Le commentaire a été bien supprimé');

		/**
		 * Ici je prefere rediriger vers l'article, mais j'arrive pas
		 */
       return $this->redirect($this->generateUrl('blog'));
    }

	/**
     * Cela va me servir pour recuperer le formulaire et non repeter
	 * la même chose tjs. dans les autres methodes.
     */
	protected function handlePostForm(Post $post, Request $request)
	{
		$form = $this->createForm(new PostType(), $post);

		if ($form->handleRequest($request)->isValid())
		{
			$post->setAuteur($this->getUser());
			$em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'La action a été fait');

            return $this->redirect($this->generateUrl('article', array('id' => $post->getId())));
        } else {
            return $this->render('blog/formulaire_post.html.twig', array(
                'form' => $form->createView(),
            ));
		}
	}

}
