<?php

namespace Heks\BlogBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Heks\BlogBundle\Document\Article
 *
 * @ODM\EmbeddedDocument
 * @ODM\ChangeTrackingPolicy("DEFERRED_IMPLICIT")
 */
class Article
{
    /**
     * @var MongoId $id
     *
     * @ODM\Id(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $titre
     *
     * @ODM\Field(name="titre", type="string")
     */
    protected $titre;

    /**
     * @var string $contenu
     *
     * @ODM\Field(name="contenu", type="string")
     */
    protected $contenu;

    /**
     * @var date $date
     *
     * @ODM\Field(name="date", type="date")
     */
    protected $date;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return self
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * Get titre
     *
     * @return string $titre
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return self
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string $contenu
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }
}
