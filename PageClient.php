
<?php
session_start();
if(!$_SESSION['mdp']){
    header('location: login.html');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Espace Client</title>
    <link rel="stylesheet" href="stylePC.css">
    <link href='https://fonts.googleapis.com/css?family=Varela Round' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Rethink Sans' rel='stylesheet'>
    <lin


</head>
<body>

<div class="banniere">
    <?php

    if (isset($_SESSION['nom'])) {
        echo '<h1>Ça Crous\'tille</h1>';
        echo '<h2>Bienvenue ' . htmlspecialchars($_SESSION['nom']) . '</h2>';
    } else {
        echo '<h1>Ça Crous\'tille</h1>';
    }
    ?>

    <div class="lien-deco">
        <a href="deconnexion.php?deco">Se déconnecter</a>
    </div>

</div>



<div class="cover-1">

    <div class="messageBienv">
        <h1> Mon espace client gourmand  </h1>
    </div>

    <div class="conteneur">
        <div class="forme"></div>
        <div class="forme"></div>
        <div class="forme"></div>
    </div>
</div>


</body>
</html>

