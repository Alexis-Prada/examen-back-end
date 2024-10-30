<?php
/*
Controleur : supprime le produit dans le panier

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

$produit->removeOfCart($produitSelected->get("nom"));

include "templates/pages/accueil.php";