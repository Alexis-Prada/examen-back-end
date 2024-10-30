<?php
/*
Classe _model :
    -> extension des différents objets du modèle de données
*/

class _model{
    // Attribut des differents objets
    protected $table = "";
    protected $fields = [];
    protected $links = [];

    protected $id = 0;
    protected $values = [];
    protected $targets = [];

    
    // Constructeur
    function __construct($id = null){
        // Role : initialiser l'objet à charger pour qu'il soit valide
        // Parametre : id de la ligne de l'objet à charger 
        // Retour : néant
        if($id != null){
            $this->load($id);
        }
    }

    // Méthodes
    function is(){
        // Rôle : savoir si l'objet de d'id $id est chargé
        // Paramètre : néant
        // Retour : true si il est chargé, false sinon
        return ! empty($this->id);
    }

    // Getter
    function get($name){
        // Role : obtenir la valeur de la ligne de nom $name
        // Parametre : $name -> nom du champs que l'on veut récupèrer
        // Retour : valeur du champs $name ou chaine vide si pas trouver
        if(isset($this->values[$name])){
            return $this->values[$name];
        }else{
            return "";
        }
    }

    function id(){
        // Role : récupèrer l'id de l'objet
        // Paramètre : néant
        // Retour : l'id de l'objet sinon 0
        return $this->id;
    }

    // Setter
    function set($fieldName, $value) {
        // Rôle : changer / initialiser la valeur d'un attribut
        // Paramètres : quel attrribut, quelle nouvelle valeur
        //      $fieldName : nom de l'attribut
        //      $value : nouvelle valeur
        // Retour : true si accepté, false sinon

        // Il faut stocker la valeur à l'index correspondant à l'attribut de nom $fieldName, dans $this->values
        $this->values[$fieldName] = $value;

        // On retourne true (on a pas d'infos pour vérifier si la valeur est valide, on ne peut que l'accepter)
        return true;
    }

    function getTarget($fieldName) {
        // Rôle : retourner un objet pointé par un champ
        // paramètre : 
        //      $fieldName : nom du champ
        // Retour : objet (d'une classe héritée de la classe _model), chargé avec l'objet pointé
        //       si on ne trouve pas :
        //          si champ inconnu ou pas un lien : retourne un objet _model (vide)
        //          si le champ est un lien, mais vide, ou pas d'bjet en face : le bon objet, mais pas chargé

        // At-on déjà la cible (dans $this->targets)
        if (isset($this->targets[$fieldName])) {
            return $this->targets[$fieldName];
        }


        // Est-ce que c'est un lien ?
        if ( ! isset($this->links[$fieldName])) {
            // Ce n'est pas un lien : on retourne un objet de la classe _model
            $this->targets[$fieldName] = new _model();
            return $this->targets[$fieldName];
        }

        // c'est un lien : l'objet pointé est de la classe indéiquée dans $this->links[$fieldName]
        $nomClasse = $this->links[$fieldName];
        $this->targets[$fieldName] = new $nomClasse($this->get($fieldName));

        return $this->targets[$fieldName];

    }

    function load($id) {
        // Rôle : chargement de l'objet (de ses attributs) depuis une ligne de la base de données
        // Paramètres : 
        //      $id : id du contact à charger
        // Retour : true si on l'a trouvé, false sinon

        // Passe la requête : SELECT champs FROM table WHERE id = monId
        // Construire la requête SELECT `champ1`, `champ2`, .... FROM `table` WHERE id = :id
        // On valorise :id dans un tableau pour l'excéution
        $sql = "SELECT " ;
        // Ajouter tous les noms de champs, encadrés par `, séparés par ,
        // Le noms des champs : ils sont dans l'attribut fields (tableau $this->fields)
        // On met les noms de tous les champs, encadrés par ` `, séparés par une virgule

        // faire un tableau avec les morceux de texte à séparer
        // Utiliser la fonction implode pour générer le texte compoésé de tous les éléments séparés un texte donné

        // On génère un tableau composés des noms des champs encadrés par ` ` 
        $tableau = [];
        foreach($this->fields as $nomChamp) {
            $tableau[] = "`$nomChamp`";
        }
        $sql .= implode(", ", $tableau);
        
        // Ajouter FROM puis  le nom de la table (il est dans $this->table) encadré par `
        $sql .= " FROM `$this->table` ";

        // Ajouter le texte WHERE `id` = :id
        $sql .= " WHERE `id` = :id";

        // Faire le tableau pour valoriser :id
        $param = [ ":id" => $id];

        //      Préparer / exécuter
        global $bdd;
        $req = $bdd->prepare($sql);
        if ( ! $req->execute($param)) {
            // On a une erreur de requête (on peut afficher des messages en phase de debug)
            return false;
        }

        // On s'assure que l'on a trouvé une ligne
        $listeExtraite = $req->fetchAll(PDO::FETCH_ASSOC);
        // Si le tabeu $liste est vide (0 elt), c'est qu'on a pas l'id cherché
        if (empty($listeExtraite)) {
            return false;
        }

        // Transfere son résultat dans les valeurs des attributs internes
        // On récupère le premier (et seul) élément
        $tab = $listeExtraite[0];

        // Pour chaque champ de l'objet, on valorise $this->values[champ];
        foreach($this->fields as $nomChamp) {
            $this->values[$nomChamp] = $tab[$nomChamp];
        }

        // On renseigne l'id :
        $this->id = $id;

        return true;

    }

    function listAllFromKey($field, $keyId){
        // Rôle : charger un objet à partir d'un champ différent de sa clé primaire (id) mais par une clé étrangère. Ce champ doit être la clé primaire d'une autre table. Les deux tables doivent être forcément liées. 
        // Paramètres : 
        //      $fields -> nom du champ par lequel chargé l'objet
        //      $keyId -> valeur de la clé primaire
        // Retour : true si l'objet a été trouvé et chargé, false sinon

        $sql = "SELECT * FROM $this->table WHERE `$field` = :keyId"; 
        $param = [":keyId" => $keyId];

        global $bdd;
        $req = $bdd->prepare($sql);
        if ( ! $req->execute($param)) {
            // Erreur de requête 
            return [];
        }

        $ligneRecup = $req->fetchAll(PDO::FETCH_ASSOC);

        $retour = [];
        foreach($ligneRecup as $ligne){
            $classe = get_class($this);
            $obj = new $classe($ligne["id"]);
            $retour[$ligne["id"]] = $obj;
        }
        return $retour;
    }

    function loadFromEmail($champValue){
        // Role : charger un objet à partir d'un autre de ces champs qui dooit etre unique
        // Parametre : 
        //         $champ -> nom du champs
        //         $champValue -> valeur du champs
        // Retour : true si l'objet à été trouvé et chargé, false sinon

        $sql = "SELECT * FROM $this->table WHERE `email` = :champValue"; 
        $param = [":champValue" => $champValue];

        global $bdd;
        $req = $bdd->prepare($sql);
        if(!$req->execute($param)){
            // Erreur de requête 
            return false;
        }

        $ligneRecup = $req->fetch(PDO::FETCH_ASSOC);

        if($ligneRecup === false){
            return false;
        }

        $classe = get_class($this);
        $obj = new $classe($ligneRecup["id"]);

        return $obj;
    }

    function loadFromTab($tab) {
        // Rôle : initialiser l'objet (complètement) à partir d'un tableau de données (simialire à celui réupéré par fetch)
        // Paramètres : 
        //      $tab : tableau valorisant les champs du MPD
        // Retour : true si ok, false sinon

        if (isset($tab["id"])) $this->id = $tab["id"];
        foreach($this->fields as $nomChamp) {
            if (isset($tab[$nomChamp])) 
                $this->values[$nomChamp] = $tab[$nomChamp];
        }
        return true;
    }

    function insert() {
        // Rôle : création du contact courant dans la base de données
        // paramètres : néant (on utilise les attributs de l'objet)
        // retour : true si réussi, false si échoué

        // Créer la requête : INSERT INTO `nomDeLaTable` SET `nomChamp1` = :nomChamp1, `nomChamp2` = :nomChamp2, ...
        // En paralèle, faire un tableau de valorisation des :nomChampX : [ ":nomChamp1" => valeurChamp1, ":nomChamp1" => valeurChamp2, ...]
        $sql = "INSERT INTO `$this->table` SET " . $this->makeRequestSet();
        $param  = $this->makeRequestParamForSet();

        // On prépare la requête
        global $bdd;
        $req = $bdd->prepare($sql);

        //  - on exécute cette requête
        if ( ! $req->execute($param)) {
            // Erreur sur la requête
            return false;
        }

        // ne pas oublier d'enregistrer l'id qui a été généré par la BDD
        // il est dnné par la méhode lastInsertId de l'objet $bdd
        $this->id = $bdd->lastInsertId();

        return true;

    }

    function update() {
        // Rôle : mettre à jour l'objet courant dans la base de données
        // Paramètres : néant
        // Retour : true si réussi, false si échoué

        // On va devoir exécuter une requête SQL de mise à jour (UPDATE)
        //  - Construire le texte de la requête SQL
        //          UPDATE `nomDeLaTable` 
        //              SET `nomChamp1` = :nomChamp1, `nomChamp2` = :noùmChamp2, ...
        //              WHERE `id` = :id
        //      et valoriser les paramètres
        //          [ ":id" => idLigneAModifier, ":nomChamp1" => valeurChamp1, ":nomChamp1" => valeurChamp2, ...]
        // On va donc préparer deux variables : 
        //      un texte pour la requête ($sql)
        //      un tableau pour les paramètres :xx de cette requête
        //      on construit la chaine : texte UPDATE `nomDeLaTable` SET    (nomDeLaTable est dans l'atribut table de l'objet)
        //      on va ajouter tous les champs : `nomChamp1` = :nomChamp1 (en les séparant par une virgule)
        //      on va mettre la partie finale ( WHERE ìd`= :id)
        // Et pour le tableau : on met l'élément :id (la valeur est dans l'attrribut id)
        //      et tous les champs (un par un "pour chaque" ) : attribut :nomChamp, valeur : elle est dans la tableau values
        //                      on peut aussi récupérer la valeur avec la méthode get(nomChamp)


        $sql = "UPDATE  `$this->table` SET " . $this->makeRequestSet() . " WHERE `id` = :id ";
        $param = $this->makeRequestParamForSet();
        $param[":id"] = $this->id;
           

        // On prépare la requête
        global $bdd;
        $req = $bdd->prepare($sql);

        //  - on exécute cette requête
        if ( ! $req->execute($param)) {
            // Erreur sur la requête
            return false;
        }

        return true;

    }

    function makeRequestSet() {
        // Rôle : construire la partie d'une requête de mise à jour ou de création valorisant les champs
        // paramètres : néant
        // Retour : le texte à mettre derrère SET dans une requête SQL : `nomChamp1` = :nomChamp1, `nomChamp2` = :noùmChamp2, ...

        // Je n'ai comme information disponible que :
            // - les attributs de la classe
            // - les paramètres de ma méthode (aucun dans ce cas)
            // - les élments que d'autres méthodes peuvent me donner

        // On a des bouts de texte ( `nomChamp` = :nomChamp) à fabriquer (un pour chaque champ ), et à les séparer par ,
        // Une solution est de :
        //          - fabriquer un tableau simple contenant les bouts de texte
        //          - utiliser implode pour générer la chaine de caractère finale avec les séprateurs
        
        // Fabrique un tableau simplde des bouts de texte ( `nomChamp` = :nomChamp) 
        $tableau = $this->makeTableauSimpleSet();

        // Générer le texte final :
        return implode(", ", $tableau);


    }

    function makeTableauSimpleSet() {
        // Rôle : faire un tableau contenant pour chaque champ, un élément avec le texte `nomChamp` = :nomChamp
        // paramètres : néant
        // Retour : le tableau décrit ci-dessus

        // Faire un tableau : on part d'un tableau vide
        $result = [];

        // Pour chaque champ : ajouter dans $result un élément `nomChamp` = :nomChamp
        foreach($this->fields as $nomChamp) {
            // On a le nom du champ dans $nomchamp
            $result[] = "`$nomChamp` = :$nomChamp";
        }
        return $result;

   
    }

    function makeRequestParamForSet() {
        // Rôle : préparer (et retourner) le tableau de valorisation des paramètres pour mise à jour des champs
        // Paramètres : néant
        // Retour : le tableau contenant les valeurs associées aux :nomChamp (pour chaque champ)
        //               [ ":nomChamp1" => valeur1, ":nomChamp2" => valeur2, ... ]




        // Je n'ai comme information disponible que :
            // - les attributs de la classe
            // - les paramètres de ma méthode (aucun dans ce cas)
            // - les élments que d'autres méthodes peuvent me donner

        // On doit faire un tableau, qui a un élément pour chaque champ (du modèle conceptuel) de la table
        //      pour le chmpa dont le nom est nomChamp, l'élément du tabelau résultat 
        //          à pour valeur la valeur courant du champ
        //          pour index le caractère : suivi du nom du champ
        // Pour chaque champ : il faut parcourir la liste des champs : attribut fields ($this->fields)
        $result = [];       // On part d'un tableau vide
        foreach($this->fields as $nomChamp) {
            // On doit ajouter dans le tableau result l'index :nomChamp avecla valeur du champ
            // ON doit construire $result[":nomChamp"] = valeurDuChamp;
            $index = ":$nomChamp";          
            // Valeur : elle est dans le tableau des valeurs, l'attribut values ($this->values)
            // Si on a une valeur pour $nomChamp, on crée l'élément de tableau avec cette valeur,
            // Sinon, on crée avec null
            if (isset($this->values[$nomChamp])) {
                $result[$index] = $this->values[$nomChamp];
            } else {
                $result[$index] = null;
            }
        }

        return $result;

    }

    function delete() {
        // Rôle : supprimer l'objet courant dans la base de données
        // Paramètres : néant
        // Retour : Ceux qui sont sur les cp

        // On va devoir exécuter la requête de suppression d'une ligne

        // En PHP, pour faire une requête sur la BDD :
        //  - on construit le texte de la requête en langage en langae SQL
        // La requête à construire : DELETE FROM `nomDeLaTable` WHERE `id` = lidQueJeVeux
        // Les parties "variables" sont : 
        //      - nomDeLaTable : elle est das l'attribut table de l'objet courant
        //      - lidQueJeVeux : il est dans mon attribut id 
        // La syntaxe pour récupérer un attribut de l'objet courant : $this->nomAttribut
        $sql = "DELETE FROM `$this->table` WHERE `id` = :id";
        $param = [":id" => $this->id];
    
        //  - on préparer un objet requête
        // Cela s'aplique à l'objet base de données : il est en variable globale, c'est $bdd
        global $bdd;
        $req = $bdd->prepare($sql);

        //  - on exécute cette requête
        if ( ! $req->execute($param)) {
            // Erreur sur la requête
            return false;
        }
        //  - on exploite le résultat
        // Marquer le fait que cette objet n'est plus dans la BDD : la régle choisie est de mettre l'id à 0
        $this->id = 0;

        return true;

    }

    function listAll(...$tris) {
        // Rôle : donner la liste de tous les objets de cette calsse (depuis la BDD)
        // paramètres : gérer les critères de tri
        //          "+/-nomChamp", "+/-nnomChamp", ....
        //      autant de paramètres que de critères de tri, 
        //     chaque paramètre est le nom du champ précédé de - pour un tri descedant, 
        //              optionnellement de + pour un tri ascendant
        //      $obj->tri("+nom", "+prenom")
        //  $tris : on donne mles paramètres séparés par une virgle à l'appel, 
        //        on récupère un tableau simple dans la fonction
        // retour : liste d'objet de la classe courante, indexées par les id

        // Construire la requête SQL, et ses paramètres
        // SELECT `nomChamp1`, `nomChamp2`, ... FROM `nomTable`
        //      ORDER BY tri1 ASC/DESC, ....

        $sql = "SELECT "; 
        // Construire la liste des champs encadrés par ` 
        // On met d'abord l'id
        $tableau = [ "`id`" ];
        foreach($this->fields as $nomChamp) {
            $tableau[] = "`$nomChamp`";
        }

        $sql .= implode(", ", $tableau);
        $sql .= " FROM `$this->table` ";

        // Construire la liste des critères de tri
        $tabOrder = [];
        foreach($tris as $tri) {
            // tri : +nomChamp ou - nomChamp ou nomChamp
            $car1 = substr($tri, 0, 1);
            if ($car1 === "-") {
                $ordre = "DESC";
                $nomField = substr($tri, 1);
            } else if ($car1 === "+") {
                $ordre = "ASC";
                $nomField = substr($tri, 1);
            } else {
                $ordre = "ASC";
                $nomField = $tri;
            }
            $tabOrder[] = "`$nomField` $ordre";
        }
        if (!empty($tabOrder))  $sql .= " ORDER BY " . implode(",", $tabOrder);

        // préparer / exécuter
        global $bdd;
        $req = $bdd->prepare($sql);
        if ( ! $req->execute()) {
            // Echec de la requête
            return [];
        }

        // Construire le tableau résultat
        $result = [];
        // tant que j'ai une ligne de résultat de la requête à lire
        while ($tabObject = $req->fetch(PDO::FETCH_ASSOC)) {
            // "transférer" $tabObject en objet de la classe courante
            // Récupération du nom de la classe de l'objet courant
            $classe = get_class($this);
            $obj = new $classe();
            // Charger l'objet
            $obj->loadFromtab($tabObject);
            // ON ajoute cela dans $result
            $result[$obj->id()] = $obj;
        }

        return $result;
    }

    function getIdFromJoin($sql, $param){
        // Rôle : Récupèrer une ou plusieurs lignes lié par deux tables
        // Parametres : $sql -> requête sql | $param -> tableau des paramètres
        // Retour : tableau 
        global $bdd;
        $req = $bdd->prepare($sql);
        if(! $req->execute($param)){
            return false;
        }
        $tableau = $req->fetchAll(PDO::FETCH_ASSOC);

        foreach($tableau as $idRecup) {
            $tableauId[] = $idRecup["id"];
        }

        if(isset($tableauId)){
            return $tableauId;
        }
    }

    function sqlToList($sql, $param) {
        // Role : à partir d'ubne requête SQL et de ses paramètres, 
        //      générer une liste d'objets
        // Paramètres:
        //      $sql : texte de la requête SQL (avec des parametres :xxx)
        //      $param : tableau de valorisation des paramètres de la requête
        // Retour : tableau d'objets de la classe courante (indexés par l'ID)

        global $bdd;
        $req = $bdd->prepare($sql);

        if ( ! $req->execute($param)) {
            // Erreur sur la requête
            return false;
        }

        $tableau = $req->fetchAll(PDO::FETCH_ASSOC);

        $tabObj = [];
        foreach($tableau as $elmt){
            $objet = new $this;
            $objet->load($elmt["id"]);
            $tabObj[$objet->id()] = $objet;
        }

        return $tabObj;
    }

    function listChamps() {
        // Rôle : construire la liste des champs de la table pour une requête SELECT
        // Paramètres : néant
        // Retour : texte du type ìd`, `nom`, `prenom`
        $tab = [];
        foreach($this->fields as $nomChamp){
            $tab[] = "`$nomChamp`";
        }

        return implode(", ", $tab);
    }

    function toTab() {
        // Rôle : retourner un tableau des valeurs des champs de cet objet
        // Paramètres : néant
        // Retour : tableau des valeurs indexé par le nom des champs
        //          exemple : [ "id" => 12, "nom" => "Blanchot", "prenom" => "Christophe"]
        $tableau = [];
        foreach($this->fields as $champ){
            $tableau[$champ] = $this->values[$champ];
        }
        return $tableau;
    }

    function sqlExecute($sql, $param) {
        // Role : exécuter une requête SQL sur la BDD
        // Paramètres:
        //      $sql : texte de la requête SQL (avec des parametres :xxx)
        //      $param : tableau de valorisation des paramètres de la requête
        // Retour : 
        //      Objet requete exécutée (requête au sens PDO, que l'on pourra donc interroger comme on veut)

        global $bdd;
        $req = $bdd->prepare($sql);

        if ( ! $req->execute($param)) {
            // Erreur sur la requête
            return false;
        }
        return $req;        
    }
}