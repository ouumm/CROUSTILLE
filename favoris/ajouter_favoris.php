<?php
error_log('Début du fichier ajouter_favoris.php');

session_start();

if (!isset($_SESSION['ID'])) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
    exit();
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=espace_membre;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion à la base de données']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restaurantId'], $_POST['userId'])) {
    $userID = $_POST['userId'];
    $restaurantID = $_POST['restaurantId'];

    $checkQuery = $bdd->prepare('SELECT * FROM favoris WHERE user_id = :userID AND restaurant_id = :restaurantID');
    $checkQuery->execute(['userID' => $userID, 'restaurantID' => $restaurantID]);

    if ($checkQuery->rowCount() == 0) {
        $insertQuery = $bdd->prepare('INSERT INTO favoris (user_id, restaurant_id) VALUES (?, ?)');
        $insertQuery->execute([$userID, $restaurantID]);

        echo json_encode(['success' => true, 'message' => 'Restaurant ajouté aux favoris']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Restaurant déjà ajouté aux favoris']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Données manquantes']);
}

error_log('Fin du fichier ajouter_favoris.php');
?>
