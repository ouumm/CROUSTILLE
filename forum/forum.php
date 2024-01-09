
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

// Vérification de la session
if (!isset($_SESSION['ID'])) {
    header("Location: login.html");
    exit();
}

if (isset($_POST['valider'])) {
    // Traitement de l'ajout de message ici
    if (!empty($_POST['message'])) {
        // Récupérez l'ID de l'utilisateur connecté
        $utilisateur_id = $_SESSION['ID'];
        $message = htmlspecialchars($_POST['message']);

        // Ajoutez le message à la base de données
        $ajoutMessage = $bdd->prepare('INSERT INTO message (utilisateur_id, messageEnv) VALUES (?, ?)');
        $ajoutMessage->execute(array($utilisateur_id, $message));

        header("Location: forum.php");
        exit();
    } else {
        echo "Veuillez compléter tous les champs...";
    }
}

// Récupérez les messages avec le nom de l'utilisateur depuis la base de données
$recupMessages = $bdd->query('SELECT utilisateur.nom, message.messageEnv FROM message INNER JOIN utilisateur ON message.utilisateur_id = utilisateur.ID ORDER BY message.ID DESC');

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="STYLEforum.css">
    <link href='https://fonts.googleapis.com/css?family=Varela Round' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Rethink Sans' rel='stylesheet'>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Forum</title>

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

<div class="cover-1">
    <div class="conteneur">
        <div class="forme">
            <h3>Participe au forum !</h3>
            <form method="POST" action="" align="center">
                <textarea name="message" placeholder="Écrivez votre message ici"></textarea>
                <input type="submit" name="valider" value="Poster !">
            </form>
        </div>
    </div>


    <div class="container-fluid mt-100">
        <div class="row">
            <?php
            // Affichez chaque message en tant que carte
            while ($message = $recupMessages->fetch()) {
                ?>
                <div class="col-md-12">
                    <div id= "box" class="card mb-4 ">
                        <div class="card-header">
                            <div class="media flex-wrap w-100 align-items-center"> <img src="../images/logo/utilisateur.png" class="d-block ui-w-40 rounded-circle" alt="">
                                <div class="media-body ml-3"> <a href="javascript:void(0)" data-abc="true"><?php echo htmlspecialchars($message['nom']); ?></a>
                                    <div class="text-muted small">Posté le  <?php echo date("F j, Y", strtotime('now')); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p><?php echo htmlspecialchars($message['messageEnv']); ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

</div>
<script>
    function ajusterHauteurPage() {
        var nombreMessages = <?php echo $recupMessages->rowCount(); ?>;
        //var hauteurMinimale = 0; // Hauteur minimale de la page
        var hauteurCarte = 23; // Hauteur de chaque carte

        var hauteurNouvelle = nombreMessages * hauteurCarte;

        document.getElementsByClassName('cover-1')[0].style.height = hauteurNouvelle + 'vh';
    }

    // Appeler la fonction au chargement de la page
    window.onload = ajusterHauteurPage;
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
