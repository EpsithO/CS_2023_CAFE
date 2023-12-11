<?php

namespace App\Modele;

use App\Utilitaire\Singleton_ConnexionPDO;

class Modele_jeton
{
    static function jetonAjouter(string $valeur,int $codeAction,int $idUtilisateur,string $dateFin)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare(' 
        insert into `token` ( valeur,codeAction,idUtilisateur,dateFin) 
        VALUE ( :valeur, :codeAction, :idUtilisateur, :dateFin)');
        $requetePreparee->bindParam('valeur', $valeur);
        $requetePreparee->bindParam('codeAction', $codeAction);
        $requetePreparee->bindParam('idUtilisateur', $idUtilisateur);
        $requetePreparee->bindParam('dateFin', $dateFin);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête

        return $reponse;
    }
    static function jetonRecuperer(string $valeur){
        $connexionPDO=Singleton_ConnexionPDO::getInstance();
        $requetePreparee=$connexionPDO->prepare('SELECT * FROM token WHERE valeur=:valeurToken AND dateFin > NOW()');
        $requetePreparee->bindParam("valeurToken",$valeur);
        $reponse = $requetePreparee->execute();
        $tableau=$requetePreparee->fetchAll(\PDO::FETCH_ASSOC);

        return $tableau;
    }
    static function jetonRecupererDateFin(string $valeur){
        $connexionPDO=Singleton_ConnexionPDO::getInstance();
        $requetePreparee=$connexionPDO->prepare('SELECT dateFin FROM token WHERE valeur=:valeurToken');
        $requetePreparee->bindParam("valeurToken",$valeur);
        $reponse = $requetePreparee->execute();
        $tableau=$requetePreparee->fetchAll(\PDO::FETCH_ASSOC);

        return $tableau;
    }



    static function jetonSupprimer(string $valeur){
        $connexionPDO=Singleton_ConnexionPDO::getInstance();
        $requetePrepare=$connexionPDO->prepare('DELETE FROM token WHERE valeur= :valeurJeton');
        $requetePrepare->bindParam('valeurJeton', $id);
        $reponse=$requetePrepare->execute();
        return $reponse;
    }
    static function jetonModifier(int $id,int $codeAction){
        $conexionPDO=Singleton_ConnexionPDO::getInstance();
        $requetePrepare=$conexionPDO->prepare("UPDATE token SET codeAction=:codeAction WHERE id=:id");
        $requetePrepare->bindParam('codeAction', $codeAction);
        $requetePrepare->bindParam('id', $id);
        $reponse=$requetePrepare->execute();
        return $reponse;
    }



}