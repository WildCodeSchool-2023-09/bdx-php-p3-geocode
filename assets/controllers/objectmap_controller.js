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
        let map = L.map("map").setView({ lon: longitude, lat: latitude }, 15);
        map.setMinZoom(3);
        //map.setZoom(15);
        L.marker([latitude, longitude ], {icon: terminalIcon})
        .bindPopup("plop")
        .addTo(map);

        displayMap();

      // initialize Leaflet
        function displayMap()
        {
            L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution:
                '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>',
            }).addTo(map);

          // show the scale bar on the lower left corner
            L.control.scale({ imperial: true, metric: true }).addTo(map);
        }
    }
}
