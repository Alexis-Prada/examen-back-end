<?php
/*
Controleur : affiche la liste des commandes trié par leur statut

Paramètres : néant

*/

// Initialisation
require_once "utils/init.php";

$commande = new commande();

include "templates/pages/liste_commande.php";