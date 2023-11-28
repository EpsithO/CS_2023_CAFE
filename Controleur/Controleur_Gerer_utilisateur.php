<?php

use App\Modele\Modele_categorie_utilisateur;
use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_AfficherMessage;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;
use App\Vue\Vue_Utilisateur_Formulaire;
use App\Vue\Vue_Utilisateur_Liste;
use function App\Fonctions\genererMotDePasseAléatoire;
use function App\Fonctions\SendMailWithpasswordWithPhpMailer;

$Vue->setEntete(new Vue_Structure_Entete());


$Vue->setMenu(new Vue_Menu_Administration());

switch ($action) {
    // Niveau d'autorisation 1 : SuperAdmin : peut tout faire.
    // Niveau d'autorisation 2 : Il peut modfier une entreprise et catalogue
    // Niveau d'autorisation 3 : Il peut juste gérer le catalogue

    case "ModifierUtilisateur":
        //Modifier dans le formulaire de mise à jour
        $listeNiveauAutorisation = Modele_categorie_utilisateur::categorie_utilisateur_Select();
        $Utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_REQUEST["idUtilisateur"]);
        $Vue->addToCorps(new Vue_Utilisateur_Formulaire(false, $listeNiveauAutorisation, $Utilisateur["idUtilisateur"], $Utilisateur["login"], $Utilisateur["idCategorie_utilisateur"]));

        break;
    case "mettreAJourUtilisateur":

        //Mettre à jour dans la liste des entreprises
        Modele_Utilisateur::Utilisateur_Modifier($_REQUEST["idUtilisateur"], $_REQUEST["login"], $_REQUEST["niveauAutorisation"]);
        $Utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_REQUEST["idUtilisateur"]);

        $listeUtilisateur = Modele_Utilisateur:: Utilisateur_Select_Cafe();
        $Vue->addToCorps(new Vue_Utilisateur_Liste($listeUtilisateur));

        break;
    case "réinitialiserMDPUtilisateur":
        //Réinitialiser MDP sur la fiche de l'entreprise

        $mdp = genererMotDePasseAléatoire(10);
        SendMailWithpasswordWithPhpMailer($_REQUEST["email"],$mdp);


        $Utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_REQUEST["idUtilisateur"]);
        Modele_Utilisateur::Utilisateur_Modifier_motDePasse($_REQUEST["idUtilisateur"], $mdp); //$Utilisateur["idUtilisateur"]

        $listeUtilisateur = Modele_Utilisateur:: Utilisateur_Select_Cafe();
        $Vue->addToCorps(new Vue_Utilisateur_Liste($listeUtilisateur));

        break;
    case "nouveau":
        //Nouveau sur la liste des utilisateurs
        $listeNiveauAutorisation = Modele_categorie_utilisateur::categorie_utilisateur_Select();
        $Vue->addToCorps(new Vue_Utilisateur_Formulaire(true, $listeNiveauAutorisation));

        break;
    case "buttonCreerUtilisateur":
        // On regarde si le login est disponible : il ne faut pas que deux personnes aient le même login !
        $login_nouveau = $_REQUEST["login"];
        $listeUtilisateur = Modele_Utilisateur:: Utilisateur_Select_Cafe();
        $login_deja_attribue = false;
        for ($i = 0; $i < count($listeUtilisateur); $i++) {
            $iemeUtilisateur = $listeUtilisateur[$i];
            if ($login_nouveau == $iemeUtilisateur["login"]) {
                $login_deja_attribue = true;
            }
        }
        if ($login_deja_attribue == true) {
            $listeNiveauAutorisation = Modele_categorie_utilisateur::categorie_utilisateur_Select();
            $Vue->addToCorps(new Vue_Utilisateur_Formulaire(true, $listeNiveauAutorisation));
            $Vue->addToCorps(new Vue_AfficherMessage("<br><label><b>Erreur : Ce login est déjà attribué, veuillez saisir un autre login</b></label>"));
        } else {
            //Créer sur la fiche de création d'une utilisateurs
            Modele_Utilisateur::Utilisateur_Creer($_REQUEST["login"], "secret", $_REQUEST["codeCategorie"]);
            //Redirect_Self_URL();
            $listeUtilisateur = Modele_Utilisateur:: Utilisateur_Select_Cafe();
            $Vue->addToCorps(new Vue_Utilisateur_Liste($listeUtilisateur, "Utilisateur créé"));
        }

        break;
    case "DesactiverUtilisateur":
        //Désactiver utilisateur ou réactiver utilisateur
        $Utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_REQUEST["idUtilisateur"]);
        // champ desactiver valeur 0 : personne activée sur le site

        $Utilisateur["desactiver"] = 1;
        Modele_Utilisateur::Utilisateur_Modifier_Desactivation($_REQUEST["idUtilisateur"], $Utilisateur["desactiver"]);

        $listeUtilisateur = Modele_Utilisateur:: Utilisateur_Select_Cafe();
        $Vue->addToCorps(new Vue_Utilisateur_Liste($listeUtilisateur));
        break;
    case "ActiverUtilisateur":
        //Désactiver utilisateur ou réactiver utilisateur
        $Utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_REQUEST["idUtilisateur"]);

        $Utilisateur["desactiver"] = 0;
        Modele_Utilisateur::Utilisateur_Modifier_Desactivation($_REQUEST["idUtilisateur"], $Utilisateur["desactiver"]);

        $listeUtilisateur = Modele_Utilisateur:: Utilisateur_Select_Cafe();
        $Vue->addToCorps(new Vue_Utilisateur_Liste($listeUtilisateur));
        break;
    default:
        //situation par défaut :
        $listeUtilisateur = Modele_Utilisateur:: Utilisateur_Select_Cafe();
        $Vue->addToCorps(new Vue_Utilisateur_Liste($listeUtilisateur));
        break;
}
/*echo '<pre>';
print_r($_REQUEST);
echo '</pre>';*/

$Vue->setBasDePage(new Vue_Structure_BasDePage());
