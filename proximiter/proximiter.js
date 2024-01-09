var map;
var directionsService;
var directionsDisplay;
var currentInfowindow = null;
var jsonData;
var userLatLng = null;
var markers = [];


window.addEventListener('load', initMap);

function initMap() {
    console.log('Initializing the map...');
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 48.8566, lng: 2.3522 },
        zoom: 11.8,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    map.addListener('click', function () {
        if (currentInfowindow) {
            currentInfowindow.close();
        }
    });

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer;
    directionsDisplay.setMap(map);

    autocompletion('start');
    let indice = 0;
    $.ajax({
        url: '../json/resto_crous.json',
        dataType: 'json',
        success: function (json) {
            console.log('JSON data loaded successfully.');
            jsonData = json;
            for (let i = 0; i < jsonData.length; i++) {
                indice += 1;
                addMarker(jsonData[i], indice);
            }
        },
        error: function (error) {
            console.error('Erreur lors du chargement du fichier JSON', error);
        }
    });

    let geo = document.getElementById('geolocalisation');
    geo.addEventListener('click', function () {
        geolocalisation();
    });

    let btnRecherche = document.getElementById("recherche")
    btnRecherche.addEventListener("click", function () {
        clearMarkers();
        GeocodeAdresseSaisie();
    });


}

function addMarker(place, i) {
    let marker = new google.maps.Marker({
        position: { lat: place.fields.geolocalisation[0], lng: place.fields.geolocalisation[1] },
        map: map,
        title: place.fields.title
    });

    markers.push(marker);

    let truncatedContact = TextTraiter(place.fields.contact);

    let popupContent = `<div class="popup-container"><h4>${place.fields.title}</h4><p> Adresse : ${truncatedContact}</p>`;

    popupContent += `<button id="btnpop" onclick="addToFavorites(${i})">Ajouter en favoris</button></div>`; // Pass the index to addToFavorites

    let infowindow = new google.maps.InfoWindow({
        content: popupContent,
        id: i
    });

    marker.addListener('click', function () {
        if (currentInfowindow) {
            currentInfowindow.close();
        }
        infowindow.open(map, marker);
        currentInfowindow = infowindow;



        calculerItineraire(userLatLng, { lat: place.fields.geolocalisation[0], lng: place.fields.geolocalisation[1] });
    });
}






function Marker(infowindow, marker, place) {
    marker.addListener('click', function () {
        if (currentInfowindow) {
            currentInfowindow.close();
        }
        infowindow.open(map, marker);
        currentInfowindow = infowindow;

        calculerItineraire(userLatLng, { lat: place.fields.geolocalisation[0], lng: place.fields.geolocalisation[1] });
    });
}

function TextTraiter(contactText) {
    let endIndex = contactText.indexOf("Téléphone") !== -1 ? contactText.indexOf("Téléphone") : contactText.indexOf("E-mail");
    return endIndex !== -1 ? contactText.substring(0, endIndex) : contactText;
}

function autocompletion(id) {
    let input = document.getElementById(id);
    let autocomplete = new google.maps.places.Autocomplete(input);
}

function geolocalisation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            CentrerCarte(position);
        }, function (error) {
            if (error.code === 1) {
                alert("Si vous ne souhaitez pas partager votre position, vous pouvez directement saisir l'adresse de départ que vous souhaitez.");
            }
        });
    } else {
        alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
    }
}


function CentrerCarte(position) {
    userLatLng = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
    };

    console.log('User location:', userLatLng);

    clearMarkers();
    CalculerDistance(userLatLng);
}

function CalculerDistance(userLatLng) {
    let radiusInKm = 5;
    let nearbyCrous = jsonData.filter(function (crous) {
        let crousLatLng = {
            lat: crous.fields.geolocalisation[0],
            lng: crous.fields.geolocalisation[1]
        };
        let distance = google.maps.geometry.spherical.computeDistanceBetween(
            new google.maps.LatLng(userLatLng),
            new google.maps.LatLng(crousLatLng)
        );
        return distance <= radiusInKm * 1000; // Convert radius to meters
    });

    // Masquer la div itinéraire
    document.getElementById("itineraire").style.display = "none";

    // Afficher la nouvelle div avec les adresses des CROUS
    let adressesCrousDiv = document.getElementById("adressesCrous");
    adressesCrousDiv.style.display = "block";


    for (let i = 0; i < nearbyCrous.length; i++) {
        let AdressCrousTraiter = TextTraiter(nearbyCrous[i].fields.contact);
        let crousLatLng = {
            lat: nearbyCrous[i].fields.geolocalisation[0],
            lng: nearbyCrous[i].fields.geolocalisation[1]
        };

        // Ajoutez un événement de clic à chaque adresse affichée
        adressesCrousDiv.innerHTML += "<p class='adresse-crous' data-lat='" + crousLatLng.lat + "' data-lng='" + crousLatLng.lng + "'>" + nearbyCrous[i].fields.title + " - " + AdressCrousTraiter + "</p>";

        addMarker(nearbyCrous[i]);
    }

    map.setCenter(userLatLng);
    map.setZoom(12.2);

    let userMarker = new google.maps.Marker({
        position: userLatLng,
        map: map,
        title: "Votre position",
        icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
    });


    adressesCrousDiv.addEventListener('click', function(){
        if (event.target.classList.contains('adresse-crous')) {
            let crousLatLng = {
                lat: parseFloat(event.target.getAttribute('data-lat')),
                lng: parseFloat(event.target.getAttribute('data-lng'))
            };
            userMarker.setMap(null);
            calculerItineraire(userLatLng, crousLatLng);
        }
    });


}

function calculerItineraire(userLatLng, crousLatLng) {
    var selectedMode = document.getElementById('modeTransport').value;

    let request = {
        origin: userLatLng,
        destination: crousLatLng,
        travelMode: selectedMode
    };

    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);

            document.getElementById("adressesCrous").style.display = 'none';

            let route = response.routes[0];

            let routeDetails = document.getElementById("fin");

            routeDetails.innerHTML = '<div id="tout" >' + '<h2>' + "la durée du trajet est de" + route.legs[0].duration.text + '</h2>';

            for (let i = 0; i < route.legs.length; i++) {
                let leg = route.legs[i];
                for (let j = 0; j < leg.steps.length; j++) {
                    let step = leg.steps[j];
                    routeDetails.innerHTML += '<p>' + step.instructions + '</p>';
                }
            }

            routeDetails.innerHTML += '<button id="retour" onclick="retourAdresses()"> Retour</button>';
            routeDetails.innerHTML += '<button id="accueil" onclick="reloadPage()"> Nouvelle recherche </button>';

            routeDetails.style.display = "block";

        } else {
            alert('Erreur lors du calcul de l\'itinéraire : ' + status);
        }
    });
}



function reloadPage() {
    location.reload();
}

function GeocodeAdresseSaisie() {
    let adresseDepart = document.getElementById('start').value;
    if (adresseDepart) {
        let geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': adresseDepart }, function (results, status) {
            if (status == 'OK') {
                userLatLng = {
                    lat: results[0].geometry.location.lat(),
                    lng: results[0].geometry.location.lng()
                };

                console.log('Geocoded address:', userLatLng);

                CalculerDistance(userLatLng);
            } else {
                alert('Veuillez saisir une adresse valide.');
            }
        });
    }
}

function clearMarkers() {
    for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
}

function retourAdresses() {
    document.getElementById("fin").style.display = "none";
    document.getElementById("adressesCrous").style.display = "block";
}

function getUserid() {
    var userInfo = null;

    $.ajax({
        type: 'GET',
        url: 'get_user_id.php',
        dataType: 'json',
        async: false,
        success: function (response) {
            userInfo = response;
            ;
        },
        error: function (error) {
            console.error('Erreur lors de la récupération des informations de l\'utilisateur', error);
        }
    });
    console.log(userInfo)
    return userInfo;
}
function addToFavorites(placeId) {
    console.log('Adding to favorites - Place ID:', placeId);

    var userId = getUserid();

    if (userId !== null) {
        var restaurantId = placeId;

        $.ajax({
            type: 'POST',
            url: 'ajouter_favoris.php',
            data: {
                restaurantId: restaurantId,
                userId: userId
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    console.log('Restaurant ajouté aux favoris');
                } else {
                    console.error('Erreur lors de l\'ajout aux favoris:', response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Erreur AJAX:', status, error);
                console.error('Réponse serveur:', xhr.responseText);
            }
        });
    } else {
        window.location.href = 'login.html';
    }
}
