<?php
// src/Heks/BlogBundle/Document/Utilisateur.php
namespace Heks\BlogBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * @MongoDB\Document
 * @MongoDBUnique(fields="nom")
 */
class Utilisateur
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $nom;

    /**
     * @MongoDB\String
     */
    protected $password;

    /**
    * @MongoDB\Collection
    */
    protected $articles;

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
     * Set nom
     *
     * @param string $nom
     * @return self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Get nom
     *
     * @return string $nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set articles
     *
     * @param collection $articles
     * @return self
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
        return $this;
    }

    /**
     * Get articles
     *
     * @return collection $articles
     */
    public function getArticles()
    {
        return $this->articles;
    }

    public function addArticle($article){
      $this->articles[] = $article;
    }
}
