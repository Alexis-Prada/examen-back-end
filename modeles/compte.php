<?php
// Classe compte

class compte extends _model{
    // Atributs de l'objet compte :
    protected $table = "compte";
    protected $fields = ["nom", "prenom", "email", "mdp", "role"];


}