<?php
/*
Controleur : affiche le détail d'un produit. C'est a dire afficher un overlay sur la page avec le detail du produit

Paramètres :
    GET -> id du produit

*/

// Initialisation
require_once "utils/init.php";

$produitSelected = new produit($_GET["id"]);

$produit = new produit();
$categorie = new categorie();
$listeCategorie = $categorie->listAll();

$listeProduit = $produit->getProduitFromCategory($produitSelected->get("idCategorie"));

$_SESSION["overlay"] = "ok";


include "templates/pages/accueil.php";