<?php
//error_log("page debut");
session_start();
include_once "vendor/autoload.php";

use App\Utilitaire\Vue;
use App\Vue\Vue_AfficherMessage;
use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Structure_Entete;


//Page appelée pour les utilisateurs publics


$Vue = new Vue();

//Charge le gestionnaire de vue


if (isset($_SESSION["typeConnexionBack"])) {
    $typeConnexion = $_SESSION["typeConnexionBack"];
} else {
    $typeConnexion = "visiteur";
}
//error_log("typeConnexion : " . $typeConnexion)  ;
//utiliser en débuggage pour avoir le type de connexion
//$Vue->addToCorps(new Vue_AfficherMessage("<br>typeConnexion $typeConnexion<br>"));

//Identification du cas demandé (situation)
if (isset($_REQUEST["case"]))
    $case = $_REQUEST["case"];
else
    $case = "Cas_Par_Defaut";
//error_log("case : " . $case);
//utiliser en débuggage pour avoir le type de connexion
//$Vue->addToCorps(new Vue_AfficherMessage("<br>Case $case<br>"));

//Identification de l'action demandée
if (isset($_REQUEST["action"]))
    $action = $_REQUEST["action"];
else
    $action = "Action_Par_Defaut";
//error_log("action : " . $action);
//utiliser en débuggage pour avoir le type de connexion
//$Vue->addToCorps(new Vue_AfficherMessage("<br>Action $action<br>"));

switch ($typeConnexion) {
    case "visiteur" :
        include "Controleur/Controleur_visiteur.php";
        break;
    case "utilisateurCafe":
    case "administrateurLogiciel":
        switch ($case) {
            case "Gerer_CommandeClient":
            case "Gerer_Commande":
                include "Controleur/Controleur_Gerer_Commande.php";
                break;
            case "Gerer_entreprisesPartenaires":
                include "Controleur/Controleur_Gerer_entreprisesPartenaires.php";
                break;
            case "Gerer_utilisateur":
                include "Controleur/Controleur_Gerer_utilisateur.php";
                break;
            case "Gerer_catalogue":
                include "Controleur/Controleur_Gerer_catalogue.php";
                break;
            case "Gerer_monCompte":
                include "Controleur/Controleur_Gerer_monCompte.php";
                break;
            default:
                $Vue->setMenu(new Vue_Menu_Administration());
                break;
        }
        break;
    case "entrepriseCliente" :
    case "salarieEntrepriseCliente" :
        switch ($case) {

            case "choixmdp":
                include "Controleur/Controleur_visiteur.php";
                break;

            case "Gerer_CommandeClient":
                include "Controleur/Controleur_Gerer_CommandeClient.php";
                break;
            case "Gerer_Panier":
                include "Controleur/Controleur_Gerer_Panier.php";
                break;
            case "Gerer_MonCompte_Salarie":
                include "Controleur/Controleur_Gerer_MonCompte_Salarie.php";
                break;
            case "Gerer_monCompte" :
            case "Gerer_Entreprise" :
                include "Controleur/Controleur_Gerer_Entreprise.php";
                break;
            case "Cas_Par_Defaut":
            case "Gerer_catalogue":
            case "Catalogue_client":
            default:
                include "Controleur/Controleur_Catalogue_client.php";
                break;
        }
}
$Vue->afficher();
