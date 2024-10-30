<?php
// Classe menu_produit
// lien entre la table mennu et la table produit

class menu_produit extends _model{
    // Atributs de l'objet menu_produit :
    protected $table = "menu_produit";
    protected $fields = ["idProduit", "idMenu"];
    protected $links = ["idProduit" => "produit", "idMenu" => "menu"];
}