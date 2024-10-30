<?php
/*
Controleur : modifie une commande pour lui donne le statut prete

Paramètres :
    GET -> id commande
*/

// Initialisation
require_once "utils/init.php";

$commandeSelec = new commande($_GET["id"]);

$commandeSelec->set("statut", "prête");

$commandeSelec->update();

$commande = new commande();

include "templates/pages/liste_commande.php";