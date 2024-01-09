<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// Vérification de la session
if (!isset($_SESSION['ID'])) {
    header("Location: ../login/login.html");
    exit();
}

// Récupérez les messages avec le nom de l'utilisateur depuis la base de données
$userId = $_SESSION['ID'];
$recupMessages = $bdd->prepare('SELECT donneecrous.Adresse, donneecrous.NumeroTelephone, donneecrous.AdresseEmail FROM favoris INNER JOIN donneecrous ON favoris.restaurant_id = donneecrous.ID WHERE favoris.user_id = ? ORDER BY donneecrous.ID DESC');
$recupMessages->execute([$userId]);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='https://fonts.googleapis.com/css?family=Varela Round' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Rethink Sans' rel='stylesheet'>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../forum/STYLEforum.css">
    <link rel="stylesheet" href="favoris.css">

    <title>Favoris</title>

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
            <a id="btndeco" href="../login/deconnexion.php?deco">Se déconnecter</a>
            <a id="btnEspaceC" href="../pageClient/PageClient.php">Espace Client</a>
        </div>
    </div>
</div>


<h1 id="titre"> Vos restaurants Crous préférés ! </h1>

<div class="container-fluid mt-100">
    <div class="row">
        <?php
        while ($favori = $recupMessages->fetch()) {
            ?>
            <div class="col-md-12">
                <div id="box" class="card mb-4">
                    <div class="media flex-wrap w-100 align-items-center"></div>
                    <div class="card-body text-center">
                        <p><strong>Adresse:</strong>
                            <?php
                            if (!empty($favori['Adresse'])) {
                                echo htmlspecialchars($favori['Adresse']);
                            } else {
                                echo "Non spécifiée";
                            }
                            ?>
                        </p>

                        <?php if (!empty($favori['NumeroTelephone'])) : ?>
                            <p><strong>Numéro de téléphone:</strong> <?php echo htmlspecialchars($favori['NumeroTelephone']); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($favori['AdresseEmail'])) : ?>
                            <p><strong>Adresse Email:</strong> <?php echo htmlspecialchars($favori['AdresseEmail']); ?></p>
                        <?php endif; ?>

                        <button id ="btn"><a href="favoris_itineraire.php?adresse=<?php echo urlencode($favori['Adresse']); ?>" >Itinéraire</a> </button>
                    </div>

                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>







</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
