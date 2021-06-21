<?php

namespace App\Data;

use App\Entity\City;

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

}