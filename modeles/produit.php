<?php
// Classe produit

class produit extends _model{
    // Atributs de l'objet produit :
    protected $table = "produit";
    protected $fields = ["nom", "prix", "idCategorie", "disponible", "image"];
    protected $links = ["idCategorie" => "categorie"];

    function getProduitFromCategory($id){
        // Rôle : lister tous les produits d'une categorie donné
        // Paramètre : id de la categorie
        // Retour : $retour -> tableau d'objet produit indexé par leur id

        $produit = new produit();
        $listAll = $produit->listAll();
        $retour = [];

        foreach($listAll as $produit){
            if($produit->get("idCategorie") === $id){
                $retour[$produit->id()] = $produit;
            }
        }

        return $retour;
    }


    function addToCart($nomProduit, $idProduit, $prixProduit){
        // Rôle : ajouter le produit dans le panier et fermer l'overlay
        // Paramètre : nom du produit
        // Retour : néant
        if(!isset($_SESSION['panier'])){
            $_SESSION['panier'] = [];
        }

        $produitDejaDansPanier = false;

        foreach($_SESSION['panier'] as &$article){
            if($article['nom'] == $nomProduit){
                // Si l'article est déjà dans le panier on ajoute 1 a la quantite
                $article['quantite'] += 1;
                $produitDejaDansPanier = true;
                break;
            }
        }

        if(!$produitDejaDansPanier){
            $_SESSION['panier'][] = [
                'nom' => $nomProduit,
                'quantite' => 1,
                'prix' => $prixProduit,
                'id' => $idProduit,
            ];
        }
        $_SESSION["overlay"] = "";
    }

    function removeOfCart($nomProduit){
        // Rôle : supprimer le produit du panier
        // Paramètre : nom du produit
        // Retour : néant
        foreach($_SESSION['panier'] as $key => $article){
            if($article['nom'] == $nomProduit){
                unset($_SESSION['panier'][$key]);
                break;
            }
        }
    }

    function calculePrix(){
        // Rôle : calcule le prix total du panier
        // Paramètre : 
        // Retour : néant

        $prixTotal = 0;

        if(isset($_SESSION['panier']) && !empty($_SESSION['panier'])){
            foreach($_SESSION['panier'] as $produit){
                $prixProduit = $produit['prix'] * $produit['quantite'];
                $prixTotal += $prixProduit;
            }
            return number_format($prixTotal, 2);;
        }else{
            return $prixTotal;
        }
    }
}