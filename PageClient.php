
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


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>

<div class="banniere">
    <div class="lien-deco d-flex justify-content-between align-items-center">
        <div class="float-left">
            <h1>Ça Crous'tille</h1>
        </div>
        <div class="text-center">
            <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['nom']) ?></h2>
        </div>
        <div class="float-right" >
            <a id="btndeco" href="deconnexion.php?deco">Se déconnecter</a>
            <a id="btnEspaceC" href="accueil.html"> Accueil</a>
        </div>
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
            </a>
        </div>
        <div class="forme" id="card-3">
            <a href="proximiter.html" class="ctn">
                <h1> A proximité </h1>
                <img src="images/logo/location.png" class="img">
            </a>
        </div>
        <div class="forme" id="card-3">
            <a href="forum.php" class="ctn">
                <h1> Forum </h1>
                <img src="images/logo/forum.png" class="img">
            </a>
        </div>
        <div class="forme" id="card-3">
            <a href="favoris.php" class="ctn">
                <h1> Favoris </h1>
                <img src="images/logo/favorite.png" class="img">
            </a>
        </div>
    </div>
</div>


</body>
</html>

