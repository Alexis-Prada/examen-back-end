<div class="overlay">
    <h4><?= $produitSelected->get("nom") ?></h4>
    <p><?= $produitSelected->get("prix") ?>€</p>
    <a class="btn" href="afficher_produits_categorie.php?id=<?= $produitSelected->get("idCategorie") ?>">Annuler</a>
    <a class="btn btn-full" href="ajouter_produit_commande.php?id=<?= $produitSelected->id() ?>">Ajouter</a>
</div>