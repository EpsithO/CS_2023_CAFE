<?php
namespace App\Fonctions;
    function Redirect_Self_URL():void{
        unset($_REQUEST);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

function GenereMDP($nbChar) :string{

    return "secret";
}

function CalculComplexiteMDP($mdp) :int{

    $alphabetMin = "azertyuiopqsdfghjklmwxcvbn";
    $alphabetMaj = strtoupper($alphabetMin);
    $chiffre = "0123456789";
    $caractereSpeciaux1 = "!#$*%?";
    $caractereSpeciaux2 = "&[|]@^µ§:/;.,<>°²³";

    $boolMin = false;
    $boolMaj = false;
    $boolChiffre = false;
    $boolcaractereSpeciaux1 = false;
    $boolcaractereSpeciaux2 = false;

    $n = 0;
    foreach (str_split($mdp) as $lettre) {
        if (!$boolMin) {
            if (str_contains($alphabetMin, $lettre)){
                $boolMin =true;
                $n += 26;
            }
        }
        if (!$boolMaj) {
            if (str_contains($alphabetMaj, $lettre)){
                $boolMaj =true;
                $n += 26;
            }
        }
        if (!$boolChiffre) {
            if (str_contains($chiffre, $lettre)){
                $boolChiffre =true;
                $n += 10;
            }
        }
        if (!$boolcaractereSpeciaux1) {
            if (str_contains($caractereSpeciaux1, $lettre)){
                $boolcaractereSpeciaux1 =true;
                $n += 6;
            }
        }
        if (!$boolcaractereSpeciaux2) {
            if (str_contains($caractereSpeciaux2, $lettre)){
                $boolcaractereSpeciaux2 =true;
                $n += 18;
            }
        }
    }
    $l = strlen($mdp);
    $num = ($n**$l);
    // Convertir en binaire
    $numBinaire = '';
    while ($num > 0) {
        $bit = $num % 2;
        $num = ($num-$bit) / 2;
        $numBinaire = $bit . $numBinaire;
    }

    return strlen($numBinaire)-1;



}
