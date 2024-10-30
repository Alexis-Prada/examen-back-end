<?php
/*
Template de page : Afficher la page d'accueil 

Paramètres :
    

*/
// var_dump($_SESSION);
// exit;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wacdo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- HEADER -->
    <header class="flex space-between align-center">
        <h2>Wacdo</h2>
        <?php 
        if($_SESSION["role"] == "preparateur"){?>
        <div class="header-container-btn">
            <a class="btn btn-full" href="afficher_liste_commande.php">Commandes en cours</a>
            <a class="btn" href="deconnexion.php">Déconnexion</a>
        </div>
        <?php
        }
        ?>
    </header>
    <!-- MAIN -->
    <main>
    <?php
    if($_SESSION["role"] == "accueil"){ 
        include "templates/fragments/borne_commande.php";
    }else if($_SESSION["role"] == "preparateur"){ 
        include "templates/fragments/accueil_preparateur.php"; 
    }else if($_SESSION["role"] == "administrateur"){ 
        include "templates/fragments/accueil_administrateur.php";
    }
    ?>
    </main>
</body>
</html>