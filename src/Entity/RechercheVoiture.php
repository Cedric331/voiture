<?php 

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class RechercheVoiture{

   /**
    * @Assert\LessThanOrEqual(propertyPath="maxAnnee", message="Doit être plus petit que l'année max")
    *
    * @var [type]
    */
   private $minAnnee;

      /**
    * @Assert\GreaterThanOrEqual(propertyPath="minAnnee", message="Doit être plus grand que l'année min")
    *
    * @var [type]
    */
   private $maxAnnee;

   public function getMinAnnee()
   {
      return $this->minAnnee;
   }

   public function setMinAnnee(int $minAnnee)
   {
      $this->minAnnee = $minAnnee;
      return $this->minAnnee;
   }

   public function getMaxAnnee()
   {
      return $this->maxAnnee;
   }

   public function setMaxAnnee(int $maxAnnee)
   {
      $this->maxAnnee = $maxAnnee;
      return $this;
   }
}