<?php
/*
Template de page : Affiche la première page, le formulaire de connexion

Paramètres : néant
*/
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container-1200">
    <br>
    <h2>Formulaire de connexion</h2>
    <form method="POST" action="verif_connexion.php">
        <div>
            <label for="email">Email :</label>
            <input id="email" type="email" name="email">
        </div>
        <div>
            <label for="mdp">Mot de passe :</label>
            <input id="mdp" type="password" name="mdp">
        </div>
        <input type="submit" name="valider" value="Se connecter">
    </form>

    <br><br><br>
    <p>Compte client : alexis@prada.fr -> alexis</p>
    <p>Compte preparateur : mathilde@borel.fr -> mathilde</p>

</body>
</html>

