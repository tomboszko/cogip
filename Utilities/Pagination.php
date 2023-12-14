<?php

namespace App\Utilities;

use InvalidArgumentException;

class Pagination
{
    private $itemsPerPage;
    private $currentPage;

    // Constructeur de la classe qui prend le nombre d'éléments par page et le numéro de la page actuelle comme paramètres
    public function __construct($itemsPerPage, $currentPage)
    {
        // Vérifie si les paramètres sont valides (entiers positifs)
        if (!is_int($itemsPerPage) || !is_int($currentPage) || $itemsPerPage <= 0 || $currentPage <= 0) {
            // Lance une exception en cas de paramètres invalides
            throw new InvalidArgumentException("Invalid pagination parameters.");
        }

        // Initialise les propriétés de la classe avec les paramètres valides
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = $currentPage;
    }

    // Retourne le nombre d'éléments par page
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    // Retourne le numéro de la page actuelle, en s'assurant qu'il n'est jamais inférieur à 1
    public function getCurrentPage()
    {
        return max(1, $this->currentPage);
    }

    // Calcule le nombre total de pages en fonction du nombre total d'éléments
    public function getTotalPages($totalItems)
    {
        return ceil($totalItems / $this->itemsPerPage);
    }

    // Génère la clause LIMIT pour une requête SQL en fonction de la pagination
    public function getLimitClause()
    {
        // Calcule l'offset (décalage) en fonction de la page actuelle et du nombre d'éléments par page
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;

        // Retourne la clause LIMIT pour la requête SQL
        return "LIMIT $offset, {$this->itemsPerPage}";
    }
}
