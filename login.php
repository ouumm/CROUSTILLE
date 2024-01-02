<?php
session_start();
$insertUser = null;

    $bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['inscription'])) {
// Traitement du formulaire d'inscription ici
        if (!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['mdp'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            $mdp = sha1($_POST['mdp']);
            $checkEmail = $bdd->prepare('SELECT * FROM utilisateur WHERE email = ?');
            $checkEmail->execute(array($email));
            if ($checkEmail->rowCount() == 0) {
                $insertUser = $bdd->prepare('INSERT INTO utilisateur (nom, email, mdp) VALUES (?, ?, ?)');
                $insertUser->execute(array($nom, $email, $mdp));

                $recupUtilisateur = $bdd->prepare('SELECT * From utilisateur where nom = ? AND email= ? AND mdp= ?');
                $recupUtilisateur->execute(array($nom, $email, $mdp));
                if ($recupUtilisateur->rowcount() > 0) {
                    $_SESSION['nom'] = $nom;
                    $_SESSION['email'] = $email;
                    $_SESSION['mdp'] = $mdp;
                    $_SESSION['ID'] = $recupUtilisateur->fetch()['ID'];

                }


            }else {

                echo "Cet email est déjà utilisé par un autre utilisateur.";
            }


        } else {
            echo "Remplissez tous les champs";
        }


    } elseif (isset($_POST['soumettre'])) {
// Traitement du formulaire de connexion ici
        if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
            $email = htmlspecialchars($_POST['email']);
            $mdp = sha1($_POST['mdp']);



            $recupUtilisateur = $bdd->prepare('SELECT * FROM utilisateur WHERE email= ? AND mdp= ?');
            $recupUtilisateur->execute(array($email, $mdp));
            if ($recupUtilisateur->rowcount() > 0) {
                $_SESSION['email'] = $email;
                $_SESSION['mdp'] = $mdp;
                $_SESSION['ID'] = $recupUtilisateur->fetch()['ID'];
                echo $_SESSION['ID'];

            } else {
                echo "votre mdp ou email est incorrect";
            }


        } else {
            echo "Remplissez tous les champs";
        }
    }
}

