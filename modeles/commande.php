<?php
// Classe commande

class commande extends _model{
    // Atributs de l'objet commande :
    protected $table = "commande";
    protected $fields = ["statut", "date_commande", "idClient"];
    protected $links = ["idClient" => "compte"];


    function getCommandePrete(){
        // Role : recupere les commandes pretes
        // Paramètre : néant
        // Retour : néant

        $commande = new commande();
        $commandePrete = $commande->listAll();
        $retour = [];
        foreach($commandePrete as $cmd){
            if($cmd->get("statut") === "prête"){
                $retour[$cmd->id()] = $cmd;
            }
        }
        return $retour;
    }

    function getCommandeEnPrep(){
        // Role : recupere les commandes en cours de preparation
        // Paramètre : néant
        // Retour : néant

        $commande = new commande();
        $commandeEnPrep = $commande->listAll();
        $retour = [];
        foreach($commandeEnPrep as $cmd){
            if($cmd->get("statut") === "en préparation"){
                $retour[$cmd->id()] = $cmd;
            }
        }
        return $retour;
    }
    
}