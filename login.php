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

                    echo "<script language='JavaScript'>";
                    echo "alert('Votre compte a été crée ! veuillez vous connecter !');";
                    echo "window.location.href='login.html';";
                    echo "</script>";

                }


            }else {

                echo "<script language='JavaScript'>";
                echo "alert('Cet email est déjà utilisé par un autre utilisateur.');";
                echo "window.location.href='login.html';";
                echo "</script>";


            }


        } else {

            echo "<script language='JavaScript'>";
            echo "alert('Remplissez tous les champs');";
            echo "window.location.href='login.html';";
            echo "</script>";

        }


    } elseif (isset($_POST['soumettre'])) {
        // Traitement du formulaire de connexion ici
        if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
            $email = htmlspecialchars($_POST['email']);
            $mdp = sha1($_POST['mdp']);

            $recupUtilisateur = $bdd->prepare('SELECT * FROM utilisateur WHERE email = ? AND mdp = ?');
            $recupUtilisateur->execute(array($email, $mdp));

            if ($recupUtilisateur->rowCount() > 0) {
                $utilisateur = $recupUtilisateur->fetch();
                $_SESSION['nom'] = $utilisateur['nom'];
                $_SESSION['email'] = $email;
                $_SESSION['mdp'] = $mdp;
                $_SESSION['ID'] = $utilisateur['ID'];

                header("Location: PageClient.php");
                exit();
            } else {
                echo "<script language='JavaScript'>";
                echo "alert('Votre mot de passe ou votre email est incorrect');";
                echo "window.location.href='login.html';";
                echo "</script>";
            }
        } else {
            echo "<script language='JavaScript'>";
            echo "alert('Remplissez tous les champs');";
            echo "window.location.href='login.html';";
            echo "</script>";
        }
    }
}
