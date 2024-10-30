<?php
/*
Controleur : ajoute le produit dans le panier

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

$produit->addToCart($produitSelected->get("nom"), $produitSelected->id(), $produitSelected->get('prix'));

include "templates/pages/accueil.php";