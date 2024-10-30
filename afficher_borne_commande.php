<?php
/*
Controleur : affiche la borne de commande (page accueil)

Paramètres :

*/

// Initialisation
require_once "utils/init.php";


$produit = new produit();
$categorie = new categorie();
$listeCategorie = $categorie->listAll();

$listeProduit = $produit->getProduitFromCategory("1");

$_SESSION["overlay"] = "";


include "templates/pages/accueil.php";