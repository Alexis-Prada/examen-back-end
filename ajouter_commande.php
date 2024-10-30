<?php
/*
Controleur : Ajoute la commande dans la bdd

Paramètres : néant
*/

// Initialisation
require_once "utils/init.php";

$tabValues = [];
if($userConnected->get("role") == "accueil"){
    $tabValues["idClient"] = $userConnected->id();
}else if($userConnected->get("role") == "preparateur"){
    if(isset($_POST["emailClient"])){
        $client = new compte();
        $loadClient = $client->loadFromEmail($_POST["emailClient"]);
        $tabValues["idClient"] = $loadClient->id();
    }
}

$tabValues['date_commande'] = date('Y-m-d H:i:s');
$tabValues['statut'] = 'en preparation';

$panier = $_SESSION['panier'];

$commandeAdd = new commande();

if($commandeAdd->loadFromTab($tabValues)){
    $commandeAdd->insert();

    $idCommande = $bdd->lastInsertId();

    // Requête sql pour insérer chaque produit de la commande dans la table commande_produit
    $sql = "INSERT INTO `commande_produit` (`idProduit`, `idCommande`) VALUES (:produit, :commande)";
    $stmtCommande = $bdd->prepare($sql);

    // Je dois boucler sur le panier et executé la requete sql pour chaque produit
    foreach ($panier as $produit) {
        $stmtCommande->execute([
            ':produit' => $produit['id'],
            ':commande' => $idCommande,
        ]);
    }

    session_deconnect();

    unset($_SESSION["panier"]);
    
    include "templates/pages/form_connexion.php";
}else{
    echo "Ajout de la commande echoué";
    exit;
}
