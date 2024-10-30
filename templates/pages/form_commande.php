<?php
/*
Template de page : Afficher le formulaire d'ajout d'une commande 

Paramètres :
    GET -> id de la commande

*/
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
        <div class="header-container-btn">
            <a class="btn btn-full" href="afficher_liste_commande.php">Commandes en cours</a>
            <a class="btn" href="deconnexion.php">Déconnexion</a>
        </div>
    </header>
    <!-- MAIN -->
    <main>
        <form method="POST" action="ajouter_commande.php">
            <label for="emailClient">Email :</label>
            <input type="email" id="email-client" name="emailClient">
            <input type="submit" name="valider" value="Valider">
        </form>
    </main>
</body>
</html>