<?php
/*
Fonctions de gestion de la session

On a une superglobale $_SESSION (on en fait un tableau)
   - quand PHP est fini : elle est enregistrée
   - quand on lance session_start (un controleur) : elle est restaurée

   On va gérer cette variable de la manière suivante :
    - l'index id contiendra 0 (ou n'existera pas) quand aucun utilisateur n'est connecté
    - si un utilisateur est connecté $_SESSION["id"] contient l'id de l'utilisateur

    quand un utilisateur est connecté, on va stocker l'objet associé dans la variable globale $utilisateurConnecte
*/

function session_activation() {
    // Rôle : active le mécanisme de session
    // paramètres : néant
    // retour : true si on est connecté, false sinon

    // démarrer le maécanisme
    if(!session_isconnected()){
        session_start();
        $_SESSION["role"] = '';
    }
    // session_start();


    // Si on a un utilisateur connecté, on le charge dans $utilisateurConnecte (variable globale)
    global $userConnected;
    if(session_isconnected()){
        $userConnected = new compte(session_idconnected());
        $_SESSION["role"] = $userConnected->get("role");
    }

    // Si on n'a pas réussi à charger l'utilisateur
    // if ( ! $userConnected->is() ) {
    //     session_deconnect();
    // }

    // Retourner si on est connecté ou pas
    return session_isconnected();

}

function session_isconnected() {
    // Rôle : dire si il y a une connexion active ou pas
    // Paramètres : néant
    // Retour : true is on est connect, false sinon

    return ! empty($_SESSION["id"]);
}

function session_idconnected() {
    // Rôle : donné l'id de l'utilisateur connexté
    // Paramètres : néant
    // Retour : l'id ou 0

    if (empty($_SESSION["id"])) {
        return 0;
    } else {
        return $_SESSION["id"];
    }

}

function session_userconnected() {
    // Rôle : donné l'objet correspondant à l'utilisateur connecté
    // Paramètres : néant
    // Retour : un objet de la calsse qui gère les utilisateurs de l'appli

    global $userConnected;
    return $userConnected;

}

function session_deconnect() {
    // Rôle : déconnecter la session courante
    // paramètres : néant
    // Retour : true

    // Mettre 0 dans $_SESSION["id"]
    $_SESSION["id"] = 0;
    $_SESSION["compte"] = 0;

    // Vider $utilisateurConnecte
    global $userConnected;
    $userConnected = 0;
}

function session_connect($id) {
    // Rôle : connecter un utilisateur
    // paramètres :
    //      $id : id de l'utilisateur connecté
    // Retour : true

    // Mettre l'id dans $_SESSION["id"]
    $_SESSION["id"] = $id;

    // Mettre à jour $utilisateurConnecte
    global $userConnected;
    $userConnected = new compte($id);

}