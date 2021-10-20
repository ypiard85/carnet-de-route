<?php

namespace App\Data;

use App\Entity\City;

use App\Entity\Categorie;

class SearchData{

    /**
     * @var int
     */
    public $page = 1;


    /**
     * @var string
     */
     public $q = "";

     /**
     * @var string
     */
    public $categorie = "";

     /**
      * @var string
      */
     public $sujet = "";

     /**
      * @var string
      */
     public $city = "";

     /**
      * @var string
      */
      public $filter = "";

      /**
      * @var string
      */
      public $likes = "";

      /**
      * @var string
      */
      public $premium = "";

}