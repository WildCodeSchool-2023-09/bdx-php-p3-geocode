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
        if (userLon === undefined && userLat === undefined) {
            userLon = 0;
            userLat = 45;
        }
        let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon'})
        let map = L.map("map").setView({ lon: userLon, lat: userLat }, 15);


                console.log(userLon, userLat);
                console.log(typeof userLat);
                map.setMinZoom(3);
                map.setView({ lon: userLon, lat: userLat }, 15);

                //map.setZoom(15);
                L.marker({ lon: userLon, lat: userLat })
                .bindPopup("Vous êtes ici")
                .addTo(map);
                getTerminals();


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
            console.log('from map_search', userLon, userLat);
            const terminals = fetch('getterminal/' + userLon + '/' + userLat)
            .then((resp) => {return resp.json()})
            .then((data) => displayDataMap(data))
              .catch((err) => console.log(err));

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
            data.forEach(elt => {
                L.marker([elt.latitude, elt.longitude], {icon: terminalIcon}).addTo(map)
                .bindPopup(elt.address)
                console.log(elt);

            })
        }

    }
}
