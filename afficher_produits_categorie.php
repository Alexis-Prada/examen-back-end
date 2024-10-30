<?php
/*
Controleur : récupère la liste des produit de la catégorie sélectionner 

Paramètres :
    GET -> id de la catégorie 

*/

// Initialisation
require_once "utils/init.php";

$idCategorie = $_GET["id"];
$_SESSION["overlay"] = "";

$produit = new produit();
$categorie = new categorie();
$listeCategorie = $categorie->listAll();
$listeProduit = $produit->getProduitFromCategory($idCategorie);

include "templates/pages/accueil.php";