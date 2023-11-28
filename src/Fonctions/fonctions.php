<?php
namespace App\Fonctions;
    use App\Modele\Modele_Utilisateur;
    use Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    function Redirect_Self_URL():void{
        unset($_REQUEST);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

function genererMotDePasseAléatoire($longueur = 10):string
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$*%?&[|]@^µ§:/;.,<>°²³"';

    $longueurMax = strlen($caracteres);
    $mdp = '';
    for ($i = 0; $i < $longueur; $i++) {
        $mdp .= $caracteres[random_int(0, $longueurMax - 1)];
    }
    return $mdp;
}

function CalculComplexiteMDP($mdp) :int
{

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
            if (str_contains($alphabetMin, $lettre)) {
                $boolMin = true;
                $n += 26;
            }
        }
        if (!$boolMaj) {
            if (str_contains($alphabetMaj, $lettre)) {
                $boolMaj = true;
                $n += 26;
            }
        }
        if (!$boolChiffre) {
            if (str_contains($chiffre, $lettre)) {
                $boolChiffre = true;
                $n += 10;
            }
        }
        if (!$boolcaractereSpeciaux1) {
            if (str_contains($caractereSpeciaux1, $lettre)) {
                $boolcaractereSpeciaux1 = true;
                $n += 6;
            }
        }
        if (!$boolcaractereSpeciaux2) {
            if (str_contains($caractereSpeciaux2, $lettre)) {
                $boolcaractereSpeciaux2 = true;
                $n += 18;
            }
        }
    }
    $l = strlen($mdp);
    $num = ($n ** $l);
    // Convertir en binaire
    $numBinaire = '';
    while ($num > 0) {
        $bit = $num % 2;
        $num = ($num - $bit) / 2;
        $numBinaire = $bit . $numBinaire;
    }

    return strlen($numBinaire) - 1;
}


    function SendMailWithpasswordWithPhpMailer($mail, $mdp):void
    {
        $mdp = genererMotDePasseAléatoire(10);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = '127.0.0.1';
        $mail->Port = 1025; //Port non crypté
        $mail->SMTPAuth = false; //Pas d’authentification
        $mail->SMTPAutoTLS = false; //Pas de certificat TLS
        $mail->setFrom('test@labruleriecomtoise.fr', 'admin');
        $mail->addAddress('client@labruleriecomtoise.fr', 'Mon client');
        if ($mail->addReplyTo('test@labruleriecomtoise.fr', 'admin')) {
            $mail->Subject = 'Objet : Bonjour !';
            $mail->isHTML(false);
            $mail->Body = "Voici votre mdp : " . $mdp;

            // modification du mot de passe dans la base de données

            $modification = Modele_Utilisateur::Utilisateur_Modifier_motDePasse($mdp, $_REQUEST["email"]);

            if (!$mail->send()) {
                $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';
            } else {
                $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParLogin($_REQUEST["email"]);
                $modifMDP = Modele_Utilisateur::Utilisateur_Modifier_motDePasse($utilisateur["idUtilisateur"],$mdp);

                $msg = 'Message envoyé ! Merci de nous avoir contactés.';
            }
        } else {
            $msg = 'Il doit manquer qqc !';
        }


    }

