<?php
/*
Template de page : Affiche la liste des commandes

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
        <div class="header-container-btn">
            <a class="btn btn-full" href="afficher_borne_commande.php">Borne de commande</a>
            <a class="btn" href="deconnexion.php">Déconnexion</a>
        </div>
    </header>
    <!-- MAIN -->
    <main>
        <!-- Section commande en preparation -->
        <section class="space-client-top">
            <div class="flex space-between align-center">
                <h3>Liste des Commandes en préparation</h3>
            </div>
            <div class="box-commandes flex align-center">
            <?php 
            foreach($commande->getCommandeEnPrep() as $commandeE){ ?>
                <div class="commande flex align-center justify-center">
                    <h4>Commande numéro : <?= $commandeE->id() ?></h4>
                    <p>Date et heure : <?= $commandeE->get('date_commande') ?></p>
                    <a class="btn" href="afficher_detail_commande.php?id=<?= $commandeE->id() ?>">Voir détails</a>
                    <a class="btn btn-full" href="modifier_commande_prete.php?id=<?= $commandeE->id() ?>">Prête</a>
                </div>
            <?php 
            }
            ?>
            </div>
        </section>
        <!-- Section commande prete -->
        <section>
            <div class="space-client-bottom flex space-between align-center">
                <h3>Liste des Commandes prêtes</h3>
            </div>
            <div class="box-produit flex align-center">
            <?php 
            foreach($commande->getCommandePrete() as $commandeP){ ?>
                <div class="commande flex align-center justify-center">
                    <h4>Commande numéro : <?= $commandeP->id() ?></h4>
                    <p>Date et heure : <?= $commandeP->get('date_commande') ?></p>
                    <a class="btn btn" href="afficher_detail_commande.php?id=<?= $commandeP->id() ?>">Voir détails</a>
                    <a class="btn btn-full" href="modifier_commande_livree.php?id=<?= $commandeP->id() ?>">Livrée</a>
                </div>
            <?php 
            }
            ?>
            </div>
        </section>
    
    </main>
</body>
</html>