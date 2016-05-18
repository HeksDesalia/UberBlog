<?php

namespace Heks\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Heks\BlogBundle\Document\Utilisateur;
use Heks\BlogBundle\Form\Type\EnregistrementType;
use Heks\BlogBundle\Form\Model\Enregistrement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/test/")
     */
    public function indexAction()
    {

        $utilisateurs = $this->get('doctrine_mongodb')
        ->getRepository('HeksBlogBundle:Utilisateur')
        ->findOneByNom("exel");

    if (!$utilisateurs) {
        throw $this->createNotFoundException('DTC');
    }

    return $this->render('HeksBlogBundle:Default:index.html.twig', array(
      'utilisateur' => $utilisateurs
    ));
    }

    /**
    * @Route("/article/creer")
    */
    public function createArticleAction(){
      $session = $this->get('session');
      $utilisateur = $session->get('user');

      $articles = array("JEANPASCHERE", "ALCOOLDECONTREBANDEWESH");
      $utilisateur->setArticles($articles);
      $dm = $this->get('doctrine_mongodb')->getManager();
      $dm->persist($utilisateur);
      $dm->flush();

      return new Response('On a créé l\'article !');
    }

    /**
    * @Route("/article/")
    */
    public function showArticle(){
      $utilisateurs = $this->get('doctrine_mongodb')
      ->getRepository('HeksBlogBundle:Utilisateur')
      ->findAll();

      return $this->render('HeksBlogBundle:Default:articles.html.twig', array(
        'utilisateurs' => $utilisateurs
      ));
    }
}
