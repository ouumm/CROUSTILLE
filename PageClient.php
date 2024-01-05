
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



</head>
<body>

<div class="banniere">
    <?php

    if (isset($_SESSION['nom'])) {
        $nomEnMajuscule = ucfirst(htmlspecialchars($_SESSION['nom']));
        echo '<h1>Ça Crous\'tille</h1>';
        echo '<h2>Bienvenue dans votre Espace client ' . $nomEnMajuscule  . ' ! </h2>' ;
    } else {
        echo '<h1>Ça Crous\'tille</h1>';
    }
    ?>

    <div class="lien-deco">
        <a href="deconnexion.php?deco">Se déconnecter</a>
    </div>
    <div class="lien-deco">
        <a href="accueil.html">Accueil</a>
    </div>

</div>



<div class="cover-1">

    <div class="messageBienv">
        <h1> Mon espace client gourmand  </h1>
    </div>

    <div id="card-1" class="conteneur">
        <div class="forme">
            <a href="itineraire.html" class="ctn"  >
                <h1> Itinéraire </h1>
                <img src="images/logo/itinerary.png" class="img">
            </a></div>
        <div class="forme" id="card-3">
            <a href="proximiter.html" class="ctn">
                <h1> A proximité </h1>
                <img src="images/logo/location.png" class="img">
            </a></div>
        <div class="forme" id="card-3">
            <a href="forum.php" class="ctn">
                <h1> Forum </h1>
                <img src="images/logo/parler.png" class="img">
            </a></div>
    </div>
</div>


</body>
</html>

