var map;
var directionsService;
var directionsDisplay;
var currentInfowindow = null;
var jsonData;
var userLatLng;
var markers = [];
var addressInputFromMarkerClick = false;


function initMap() {
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
    autocompletionCrous('end');

    let indice = 0;
    $.ajax({
        url: '../json/resto_crous.json',
        dataType: 'json',
        success: function (json) {
            jsonData = json;
            for (var i = 0; i < jsonData.length; i++) {
                indice +=1;
                addMarker(jsonData[i],indice);
            }
        },
        error: function (error) {
            console.error('Erreur lors du chargement du fichier JSON', error);
        }
    });

    var btnitinéraire = document.getElementById("calculeRoute");
    btnitinéraire.addEventListener("click", calculateRoute);

    document.getElementById("retour").addEventListener("click", function () {
        window.location.href = window.location.href;
    })


    document.addEventListener('click', function (event) {
        var autocompleteUl = document.getElementById('selectOptions');
        if (autocompleteUl && !autocompleteUl.contains(event.target)) {
            autocompleteUl.innerHTML = '';
        }
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


    popupContent += `<button id="btnpop" onclick="addToFavorites(${i})">Ajouter en favoris</button></div>`;

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

        let adressechamp = document.getElementById("end");
        adressechamp.value = truncatedContact;

        addressInputFromMarkerClick = true;
    });
}
function autocompletionCrous(id) {
    var input = document.getElementById(id);


        input.addEventListener('input', function () {
            updateAutocomplete(id, this.value);
        });
}

function autocompletion(id) {
    var input = document.getElementById(id);
    var autocomplete = new google.maps.places.Autocomplete(input);

}

function updateAutocomplete(inputId, inputValue) {
    var autocompleteUl = document.getElementById(`selectOptions`);
    autocompleteUl.innerHTML = ''; // Clear existing content

    var matchingAddresses = jsonData.filter(function (place) {
        return TextTraiter(place.fields.contact).toLowerCase().includes(inputValue.toLowerCase());
    });

    matchingAddresses.forEach(function (place) {
        var listItem = document.createElement('li');
        listItem.textContent = TextTraiter(place.fields.contact);
        listItem.onclick = function () {
            document.getElementById(inputId).value = place.fields.contact;
            autocompleteUl.innerHTML = '';
        };
        autocompleteUl.appendChild(listItem);
    });
}

function itineraire() {
    var start = document.getElementById('start').value;
    var end = document.getElementById('end').value;

    var selectedMode = document.getElementById('modeTransport').value;

    directionsService.route({
        origin: start,
        destination: end,
        travelMode: selectedMode
    }, function (response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
            var routeDetails = document.getElementById('route-details');
            routeDetails.innerHTML = '';

            var detailchemin = document.getElementById('typetrajet');
            detailchemin.innerHTML = '<p> La durée du trajet est de ' + route.legs[0].duration.text + '</p>'; // Ajout de la durée du trajet

            for (var i = 0; i < route.legs.length; i++) {
                var leg = route.legs[i];
                for (var j = 0; j < leg.steps.length; j++) {
                    var step = leg.steps[j];
                    routeDetails.innerHTML += '<p>' + step.instructions + '</p>';
                }
            }
        }
    });
}


function calculateRoute() {
    var start = document.getElementById('start').value;
    var end = document.getElementById('end').value;

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': start }, function (results, status) {
        if (status !== 'OK' || !results[0].geometry) {
            alert('Veuillez saisir une adresse valide.');
            return;
        }

        if (!addressInputFromMarkerClick && window.location.href.includes('itineraire.html')) {
            var endAddressFound = checkAddressInJSON(end);
            if (!endAddressFound) {
                alert('Veuillez saisir une adresse de Crous valide.');
                return;
            }
        }


        itineraire();
        document.getElementById("itineraire").style.display = 'none';

        var routeDetailsDiv = document.getElementById("detailchemin");
        routeDetailsDiv.style.display = 'block';
    });
}


function checkAddressInJSON(address) {
    for (var i = 0; i < jsonData.length; i++) {
        if (address === jsonData[i].fields.contact) {
            return true;
        }
    }
    return false;
}

function TextTraiter(contactText) {
    let endIndex = contactText.indexOf("Téléphone") !== -1 ? contactText.indexOf("Téléphone") : contactText.indexOf("E-mail");
    return endIndex !== -1 ? contactText.substring(0, endIndex) : contactText;
}

function getUserid() {
    var userInfo = null;

    $.ajax({
        type: 'GET',
        url: '../favoris/get_user_id.php',
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
            url: '../favoris/ajouter_favoris.php',
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
        window.location.href = '../login/login.html';
    }
}
