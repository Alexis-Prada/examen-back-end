<?php
/*
Controleur : Deconnecte la session en cours

Paramètres : néant

*/

// Initialisation
require_once "utils/init.php";

session_deconnect();
unset($_SESSION["panier"]);

include "templates/pages/form_connexion.php";