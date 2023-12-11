<?php

require "vendor/autoload.php";
$jetonValeur = \App\Fonctions\creerJeton();
$date = new DateTime();
$newDate = $date->format("Y-m-d");
//\App\Modele\Modele_jeton::jetonAjouter($jetonValeur,0,672,$newDate);
//$jetons=\App\Modele\Modele_jeton::jetonRecuperer();
//var_dump($jetons);

//\App\Modele\Modele_jeton::jetonSupprimer(2);
\App\Modele\Modele_jeton::jetonModifier(1, 1);