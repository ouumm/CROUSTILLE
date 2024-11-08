<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte avec Itinéraire</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="../itineraire/itineraire.css" />
</head>
<body>
<div id="page">
    <div id="map"></div>
    <img id="imglogo" src="../images/logo-cacroustille.png" alt="Logo">
    <div id="itineraire">
        <button id="proximiter"><a href="../pageClient/PageClient.php" > Espace Client </a></button>
        <h1>C'est part'ille !</h1>
        <select id="modeTransport">
            <option value="DRIVING">En voiture</option>
            <option value="WALKING">À pied</option>
            <option value="BICYCLING">À vélo</option>
            <option value="TRANSIT">En transport en commun</option>
        </select>

        <input type="text" id="start" placeholder="Adresse de départ" >

        <input type="text" id="end" placeholder="Adresse d'arrivée" value="<?php echo htmlspecialchars($_GET['adresse'] ?? ''); ?>" >

        <ul id="selectOptions" class="autocomplete"></ul>

        <button id="calculeRoute">Calculer l'itinéraire</button>
    </div>
    <div id="detailchemin">
        <h3 id="typetrajet"></h3>
        <div id="route-details" class="scrollable-div"></div>
        <button id="retour"> Nouvelle itinéraire </button>
    </div>
</div>

<script src="../itineraire/itineraire.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqGzYaV-DNosMuS8hqoLv6CGn3xjms7Oc&libraries=places&callback=initMap" async defer></script>
</body>
</html>
