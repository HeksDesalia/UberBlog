<?php

namespace Heks\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Heks\BlogBundle\Document\Article;
use Heks\BlogBundle\Form\ArticleType;

/**
 * Article controller.
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all Article documents.
     *
     * @Route("/", name="article")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {


        $dm = $this->getDocumentManager();
        $utilisateur = $this->get('session')->get('utilisateur');

        $documents = $dm->getRepository('HeksBlogBundle:Utilisateur')->findAll();

        foreach ($documents as $document) {
          $articles[] = $document->getArticles()->toArray();
        }

        return array('documents' => $articles,
                     'utilisateur' => $utilisateur);
    }

    /**
     * Displays a form to create a new Article document.
     *
     * @Route("/new", name="article_new")
     * @Template()
     *
     * @return array
     */
    public function newAction()
    {
        $document = new Article();
        $form = $this->createForm(ArticleType::class, $document);

        return array(
            'document' => $document,
            'form'     => $form->createView()
        );
    }

    /**
     * Creates a new Article document.
     *
     * @Route("/create", name="article_create")
     * @Method("POST")
     * @Template("HeksBlogBundle:Article:new.html.twig")
     *
     * @param Request $request
     *
     * @return array
     */
    public function createAction(Request $request)
    {
        $document = new Article();
        $form     = $this->createForm(ArticleType::class, $document);
        $form->handleRequest($request);

        if ($form->isValid()) {
          $utilisateur = $this->get('doctrine_mongodb')
          ->getRepository('HeksBlogBundle:Utilisateur')
          ->findOneByNom($this->get('session')->get('utilisateur')->getNom());
            $document->setDate(new \DateTime());
            $document->setContenu(nl2br($document->getContenu()));
            $utilisateur->addArticle($document);
            $dm = $this->getDocumentManager();
            $dm->persist($utilisateur);
            $dm->flush();

            return $this->redirect($this->generateUrl('article_show', array('id' => $document->getId())));
        }

        return array(
            'document' => $document,
            'form'     => $form->createView()
        );
    }

    /**
     * Finds and displays a Article document.
     *
     * @Route("/{id}/show", name="article_show")
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

        $documents = $dm->getRepository('HeksBlogBundle:Utilisateur')->findAll();
        foreach($documents as $document){
          foreach ($document->getArticles() as $article){
            if ($article->getId() == $id){
              return array(
                  'document' => $article,
              );
            }
          }
        }

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Article document.');
        }
    }

    /**
     * Displays a form to edit an existing Article document.
     *
     * @Route("/{id}/edit", name="article_edit")
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

        $document = $dm->getRepository('HeksBlogBundle:Article')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Article document.');
        }

        $editForm = $this->createForm(ArticleType::class, $document);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'document'    => $document,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Article document.
     *
     * @Route("/{id}/update", name="article_update")
     * @Method("POST")
     * @Template("HeksBlogBundle:Article:edit.html.twig")
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

        $document = $dm->getRepository('HeksBlogBundle:Article')->find($id);

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Article document.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createForm(ArticleType::class, $document);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $dm->persist($document);
            $dm->flush();

            return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }

        return array(
            'document'    => $document,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Article document.
     *
     * @Route("/{id}/delete", name="article_delete")
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
            $document = $dm->getRepository('HeksBlogBundle:Article')->find($id);

            if (!$document) {
                throw $this->createNotFoundException('Unable to find Article document.');
            }

            $dm->remove($document);
            $dm->flush();
        }

        return $this->redirect($this->generateUrl('article'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
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
