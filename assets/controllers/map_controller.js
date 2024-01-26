import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets =  ['target_name'];

    connect()
    {
        console.log('map connect');
        this.displayMap();
    }

    displayMap()
    {
        let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon'})
        let userLat = 0;
        let userLon = 0;
        let map = L.map("map").setView({ lon: userLon, lat: userLat }, 2);
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition((position) => {
                userLat = Number(position.coords.latitude.toFixed(4));
                userLon = Number(position.coords.longitude.toFixed(4));
                console.log(userLon, userLat);
                console.log(typeof userLat);
                map.setMinZoom(3);
                map.setView({ lon: userLon, lat: userLat }, 15);

                //map.setZoom(15);
                L.marker({ lon: userLon, lat: userLat })
                .bindPopup("Vous êtes ici")
                .addTo(map);
                getTerminals();
            });
        } else {
            console.log("geolocation IS NOT available");
        }
      //window.onload(displayMap(userLon, userLat));
        displayMap();

      // initialize Leaflet
        function displayMap()
        {
            // map.setView({ lon: userLon, lat: userLat }, 2);
            map.setZoom(15);
          // add the OpenStreetMap tiles
            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution:
                '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>',
            }).addTo(map);

          // show the scale bar on the lower left corner
            L.control.scale({ imperial: true, metric: true }).addTo(map);

          // show a marker on the map

        }

        function getTerminals()
        {
            console.log(userLon, userLat);
            const terminals = fetch('getterminal/' + userLon + '/' + userLat)
            .then((resp) => {return resp.json()})
              .then((data) => displayDataMap(data));

        }

        function displayDataMap(data)
        {
            // var myIcon = L.icon({
            //     iconUrl: 'build/images/logo.png',
            //     iconSize: [38, 95],
            //     iconAnchor: [22, 94],
            //     popupAnchor: [-3, -76],
            //  // shadowUrl: '../images/ev-station-fill.svg',
            //     shadowSize: [68, 95],
            //     shadowAnchor: [22, 94]
            // });

            // data.forEach(elt => {
            //     L.marker([elt.latitude, elt.longitude], {icon: terminalIcon}).addTo(map)
            //     .bindPopup(elt.id + ' <br> ' + elt.address + ' <br> ' + '<button>Reservation</button>')
            //     console.log(elt);
            //
            // })

            data.forEach(elt => {
                let marker = L.marker([elt.latitude, elt.longitude], {icon: terminalIcon}).addTo(map);

                marker.on('click', function () {
                    window.location.href = '/booking/register/' + elt.id;
                });

            marker.bindPopup(elt.id + ' <br> ' + elt.address + ' <br> ' + '<button class="button" data-terminal-id="' + elt.id + '">Reservation</button>');
            console.log(elt);
            });
        }

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('reservation-button')) {
              // Récupérez l'ID du terminal à partir de l'attribut data-terminal-id
                let id = event.target.getAttribute('data-terminal-id');

              // Générer l'URL vers la page de réservation avec l'ID du terminal
                let url = Routing.generate('app_booking_register', { id: id });

              // Redirigez l'utilisateur vers la page de réservation
                window.location.href = url;
            }
        });
    }
}
