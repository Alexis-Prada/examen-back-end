<?php
/*
Template de fragment : Afficher l'espace accueil (client). C'est à dire que je dois afficher la liste des categories ainsi que la liste des menus et le panier

Paramètre : 
        $listeMenu -> liste des menus
        $listeProduit -> liste des categories
        
*/
// var_dump($listeProduit)
//var_dump($_SESSION);
// unset($_SESSION["panier"]);
?>
<!-- Section categorie -->
<section class="space-client-top">
    <div class="flex space-between align-center">
        <h3>Liste des Catégories</h3>
    </div>
    <div class="box-categorie flex align-center">
    <?php 
    foreach($categorie->listAll() as $categ){ ?>
        <a href="afficher_produits_categorie.php?id=<?= $categ->id() ?>" class="categorie flex align-center justify-center"><?= $categ->get("nom") ?></a>
    <?php 
    }
    ?>
    </div>
</section>
<!-- Section menu -->
<section>
    <div class="space-client-bottom flex space-between align-center">
        <h3>Liste des Produits</h3>
    </div>
    <div class="box-produit flex align-center">
    <?php 
    foreach($listeProduit as $produit){ ?>
        <a href="afficher_details_produit.php?id=<?= $produit->id() ?>" class="produit flex align-center">
            <h4><?= $produit->get("nom") ?></h4>
            <p><?= $produit->get("prix") ?>€</p>
        </a>
    <?php 
    }
    ?>
    </div>
</section>
<!-- Section panier -->
<section class="panier-container">
    <h3>Panier</h3>
    <ul class="commande-container">
    <?php 
    if(!empty($_SESSION["panier"])){
        foreach($_SESSION["panier"] as $produitPanier){?>
            <li class="flex align-center space-between"><?= $produitPanier["quantite"] ?> <?= $produitPanier["nom"] ?>
                <a class="btn-close" href="supprimer_produit_panier.php?id=<?= $produitPanier['id'] ?>">X</a>
            </li>
            <?php 
        }
    }
    ?>
    </ul>
    <div class="panier-container-bottom">
        <div class="panier-prix">
            <p>TOTAL : <?= $produit->calculePrix() ?>€</p>
        </div>
        <div class="flex align-center space-between">
            <a class="btn btn-full" href="ajouter_commande.php">Valider</a>
            <a class="btn" href="deconnexion.php">Annuler</a>
        </div>
    </div>
</section>
<!-- Section Overlay -->
<?php
if($_SESSION["overlay"] == "ok"){
    include "templates/fragments/overlay.php";
}
?>


