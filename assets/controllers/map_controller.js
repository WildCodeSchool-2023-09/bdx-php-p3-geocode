import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets =  ['target_name'];

    connect()
    {
        this.displayMap();
    }

    displayMap()
    {
        let userLat = 0;
        let userLon = 0;
        let map = L.map("map").setView({ lon: userLon, lat: userLat }, 2);
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition((position) => {
                userLat = Number(position.coords.latitude.toFixed(4));
                userLon = Number(position.coords.longitude.toFixed(4));
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
        displayMap();

      // initialize Leaflet
        function displayMap()
        {
            map.setZoom(15);
          // add the OpenStreetMap tiles
            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution:
                '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>',
            }).addTo(map);

          // show the scale bar on the lower left corner
            L.control.scale({ imperial: true, metric: true }).addTo(map);
        }

        function getTerminals()
        {
            const terminals = fetch('getterminal/' + userLon + '/' + userLat + '/10000')
            .then((resp) => {return resp.json()})
              .then((data) => displayDataMap(data));
        }

        function displayDataMap(data)
        {
            const pathname = window.location.pathname;
            data.forEach(elt => {
                let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon',
                    html: '<div aria-label="' + elt.address + '">' + elt.address + '</div>'})
                let marker = L.marker([elt.latitude, elt.longitude], {icon: terminalIcon}).addTo(map);
                const url = '/booking/register/' + elt.id;
                marker.bindPopup(' <br> ' + elt.address + ' <br> ' + '<a href="' +
                url +
                '"><button class="button" data-terminal-id="' + elt.id + '">Reservation</button></a>');
            });
        }
    }
}
