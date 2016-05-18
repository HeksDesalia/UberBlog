<?php


// src/Heks/BlogBundle/Controller/AccountController.php
namespace Heks\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Heks\BlogBundle\Form\Type\RegistrationType;
use Heks\BlogBundle\Form\Model\Registration;
use Heks\BlogBundle\Document\Utilisateur;

class AccountController extends Controller
{

  /**
  * @Route("/user/creer")
  */
    public function registerAction()
    {
        $form = $this->createForm(new RegistrationType(), new Registration());

        return $this->render('HeksBlogBundle:Account:register.html.twig', array('form' => $form->createView()));
    }
}
