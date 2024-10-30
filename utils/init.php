<?php
// Code d'initialisation inclu au début des controleurs

// Aide à afficher les erreurs
ini_set("display_errors", 1);
error_reporting(E_ALL);

// Déclaration de la variable global (base de données)
global $bdd;
// J'instancie un nouveau PDO avec les codes d'accés de ma base de données 
$bdd = new PDO("mysql:host=localhost;dbname=projets_exam-back_aprada;charset=UTF8", "aprada", "g7J2p6i%R");
// Ceci permet de générer des avertissements si il y a des erreurs
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// Chargement du model
require_once "utils/model.php";

// Chargement des objets du modèle de données
require_once "modeles/categorie.php";
require_once "modeles/commande_produit.php";
require_once "modeles/commande.php";
require_once "modeles/compte.php";
require_once "modeles/menu_produit.php";
require_once "modeles/menu.php";
require_once "modeles/produit.php";


// Chargement de la session
require_once "utils/session.php";

session_activation();
