<?php
/*
Controleur : Verifie si les identifiant sont correctes et verifie quel type de compte s'est connecté pour afficher le bon fragments

Paramètres :
    Objet compte -> adresse mail et mdp

*/

// Initialisation
require_once "utils/init.php";

// Récupèrer les paramètres envoyé en POST
if(isset($_POST["email"]) && isset($_POST["mdp"])){
    $email = htmlspecialchars($_POST["email"]);
    $mdp = htmlspecialchars($_POST["mdp"]);

    // Création de la requete sql
    $sql = "SELECT * FROM `compte` WHERE `email` = :email ";
    // Preparation de l'instruction
    $stmt = $bdd->prepare($sql); 
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    if($res){
        if(password_verify($mdp, $res["mdp"])){

            session_connect($res["id"]);
            $produit = new produit();
            $listeProduit = $produit->getProduitFromCategory("1");
            $categorie = new categorie();
            $listeCategorie = $categorie->listAll();

            // Redirection vers la page accueil
            include "templates/pages/accueil.php";
        }else{
            include "templates/pages/form_connexion.php";
        }
    }else{
        include "templates/pages/form_connexion.php";
    }

}