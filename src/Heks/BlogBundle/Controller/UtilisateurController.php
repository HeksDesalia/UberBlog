<?php

namespace Heks\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\Response;
use Heks\BlogBundle\Document\Utilisateur;
use Heks\BlogBundle\Form\UtilisateurType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Utilisateur controller.
 *
 * @Route("/utilisateur")
 */
class UtilisateurController extends Controller
{
    /**
     * Lists all Utilisateur documents.
     *
     * @Route("/", name="utilisateur")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $dm = $this->getDocumentManager();

        $documents = $dm->getRepository('HeksBlogBundle:Utilisateur')->findAll();

        return array('documents' => $documents);
    }


    /**
     * Allow user to connect.
     *
     * @Route("/connect", name="utilisateur_connect")
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function connectAction(Request $request){
      $document = new Utilisateur();
      $form     = $this->createForm(UtilisateurType::class, $document);
      $form->handleRequest($request);
      var_dump($document);
      if ($form->isValid()) {
        $utilisateur = $this->get('doctrine_mongodb')
        ->getRepository('HeksBlogBundle:Utilisateur')
        ->findOneByNom($document->getNom());

        if ($utilisateur != null){
          $session = $this->get('session');
          $session->set('user', $utilisateur);
          return new Response('OK');
        }
      }

      return array(
          'document' => $document,
          'form'     => $form->createView()
      );
    }

    /**
     * Displays a form to create a new Utilisateur document.
     *
     * @Route("/new", name="utilisateur_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $document = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $document);

        if ($form->isSubmitted() && $form->isValid()) {
        // ... perform some action, such as saving the task to the database

        return $this->redirectToRoute('utilisateur_create');
        }

        return array(
            'document' => $document,
            'form'     => $form->createView()
        );
    }

    /**
     * Creates a new Utilisateur document.
     *
     * @Route("/create", name="utilisateur_create")
     *
     * @Template("HeksBlogBundle:Utilisateur:new.html.twig")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $document = new Utilisateur();
        $form     = $this->createForm(UtilisateurType::class, $document);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $dm = $this->getDocumentManager();
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('utilisateur_show', array('id' => $document->getId())));
        }

        return array(
            'document' => $document,
            'form'     => $form->createView()
        );
    }

    /**
     * Finds and displays a Utilisateur document.
     *
     * @Route("/{id}/show", name="utilisateur_show")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function showAction($id)
    {
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('HeksBlogBundle:Utilisateur')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Utilisateur document.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document' => $document,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Utilisateur document.
     *
     * @Route("/{id}/edit", name="utilisateur_edit")
     * @Template()
     *
     * @param string $id The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function editAction($id)
    {
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('HeksBlogBundle:Utilisateur')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Utilisateur document.');
        }

        $editForm = $this->createForm(UtilisateurType::class, $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document'    => $document,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Utilisateur document.
     *
     * @Route("/{id}/update", name="utilisateur_update")
     * @Method("POST")
     * @Template("HeksBlogBundle:Utilisateur:edit.html.twig")
     *
     * @param Request $request The request object
     * @param string $id       The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function updateAction(Request $request, $id)
    {
        $dm = $this->getDocumentManager();

        $document = $dm->getRepository('HeksBlogBundle:Utilisateur')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Utilisateur document.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createForm(UtilisateurType::class, $document);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('utilisateur_edit', array('id' => $id)));
        }

        return array(
            'document'    => $document,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Utilisateur document.
     *
     * @Route("/{id}/delete", name="utilisateur_delete")
     * @Method("POST")
     *
     * @param Request $request The request object
     * @param string $id       The document ID
     *
     * @return array
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If document doesn't exists
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $dm = $this->getDocumentManager();
            $document = $dm->getRepository('HeksBlogBundle:Utilisateur')->find($id);

            if (!$document) {
                throw $this->createNotFoundException('Unable to find Utilisateur document.');
            }

            $dm->remove($document);
            $dm->flush();
        }

        return $this->redirect($this->generateUrl('utilisateur'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }

    /**
     * Returns the DocumentManager
     *
     * @return DocumentManager
     */
    private function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }


}
