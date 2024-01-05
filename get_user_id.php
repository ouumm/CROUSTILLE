<?php
session_start();

if (isset($_SESSION['ID'])  ) {
    echo $_SESSION['ID'];
} else {
    echo 'null';
}


// get_user_id.php

/* Suppose you have these values in session
session_start();
$id = isset($_SESSION['ID']) ? $_SESSION['ID'] : null;
$nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : null;

// Return JSON response
header('Content-Type: application/json');
echo json_encode(array('id' => $id, 'nom' => $nom));
*/