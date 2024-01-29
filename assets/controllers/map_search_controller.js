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
        let longitude = 0;
        let latitude = 45;
        let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon'})
        let map = L.map("map").setView({ lon: longitude, lat: latitude }, 15);
        if (this.element.dataset.latitude !== undefined) {
             latitude = this.element.dataset.latitude;
             longitude = this.element.dataset.longitude;
        } else if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition((position) => {
                latitude = Number(position.coords.latitude.toFixed(4));
                longitude = Number(position.coords.longitude.toFixed(4));
                map.setView({ lon: longitude, lat: latitude }, 15);
            });
            console.log(latitude, 'LATITUDE');
        } else {
            latitude = 45;
            longitude = 0;
        }
        map.setMinZoom(3);
        map.setView({ lon: longitude, lat: latitude }, 15);

        //map.setZoom(15);
        L.marker({ lon: longitude, lat: latitude })
        .bindPopup("Vous êtes ici")
        .addTo(map);
        getTerminals();

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
            console.log('from map_search', longitude, latitude);
            fetch('getterminal/' + longitude + '/' + latitude)
              .then((resp) => {return resp.json()})
              .then((data) => displayDataMap(data))
              .catch((err) => console.log(err));
        }

        function displayDataMap(data)
        {
            const pathname = window.location.pathname;
            data.forEach(elt => {
                let marker = L.marker([elt.latitude, elt.longitude], {icon: terminalIcon}).addTo(map);
                const url = '/booking/register/' + elt.id;
                marker.bindPopup(' <br> ' + elt.address + ' <br> ' + '<a href="' +
                url +
                '"><button class="button" data-terminal-id="' + elt.id + '">Reservation</button></a>');
            });
        }
    }
}
