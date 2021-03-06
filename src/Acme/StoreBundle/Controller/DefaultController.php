<?php
// src/Acme/StoreBundle/Controller/DefaultController.php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\StoreBundle\Document\Product;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller{
  public function createAction()
  {
      $product = new Product();
      $product->setName('A Foo Bar');
      $product->setPrice('19.99');

      $dm = $this->get('doctrine_mongodb')->getManager();
      $dm->persist($product);
      $dm->flush();

      return new Response('Created product id '.$product->getId());
  }

  public function showAction($id)
  {
      $product = $this->get('doctrine_mongodb')
          ->getRepository('AcmeStoreBundle:Product')
          ->find($id);

      if (!$product) {
          throw $this->createNotFoundException('No product found for id '.$id);
      }

      // do something, like pass the $product object into a template
  }

  public function updateAction($id)
  {
      $dm = $this->get('doctrine_mongodb')->getManager();
      $product = $dm->getRepository('AcmeStoreBundle:Product')->find($id);

      if (!$product) {
          throw $this->createNotFoundException('No product found for id '.$id);
      }

      $product->setName('New product name!');
      $dm->flush();

      return $this->redirect($this->generateUrl('homepage'));
  }
}
