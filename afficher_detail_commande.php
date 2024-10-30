<?php
/*
Controleur : affiche le détail d'une commande

Paramètres :
    GET -> id de la commande 

*/

// Initialisation
require_once "utils/init.php";

$ticket = new commande($_GET["id"]);



include "templates/pages/detail_commande.php";