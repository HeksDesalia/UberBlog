<?php

namespace Heks\BlogBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Heks\BlogBundle\Document\Utilisateur;

class Enregistrement{
  /**
     * @Assert\Type(type="Heks\BlogBundle\Document\Utilisateur")
     */
    protected $utilisateur;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (boolean)$termsAccepted;
    }
}
