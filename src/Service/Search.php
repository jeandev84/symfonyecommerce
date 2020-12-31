<?php
namespace App\Service;


use App\Entity\Category;

/**
 * Class Search
 *
 * La classe Search va representer mon objet de recherche
 *
 * @package App\Service
*/
class Search
{
    /**
     * Represente la recherche text de mes utilisateurs
     *
     * @var string (str)
    */
    public $string = '';


    /**
     * Representes la liste des categories a selectionner
     *
     * @var Category[]
    */
    public $categories = [];



}