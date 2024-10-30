<?php
// Classe commande_produit
// lien entre la table commande et la table produit

class commande_produit extends _model{
    // Atributs de l'objet commande_produit :
    protected $table = "commande_produit";
    protected $fields = ["idProduit", "idCommande"];
    protected $links = ["idClient" => "client", "idCommande" => "commande"];
}