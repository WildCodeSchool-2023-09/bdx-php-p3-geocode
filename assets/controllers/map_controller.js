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
        let userLat = 0;
        let userLon = 0;
        let map = L.map("map").setView({ lon: userLon, lat: userLat }, 2);
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition((position) => {
                userLat = Number(position.coords.latitude.toFixed(2));
                userLon = Number(position.coords.longitude.toFixed(2));
                console.log(userLon, userLat);
                console.log(typeof userLat);
                map.setMinZoom(3);
                map.setView({ lon: userLon, lat: userLat }, 15);

                //map.setZoom(15);
                L.marker({ lon: userLon, lat: userLat })
                .bindPopup("Vous êtes ici")
                .addTo(map);
            });
        } else {
            console.log("geolocation IS NOT available");
        }
      //window.onload(displayMap(userLon, userLat));
        displayMap(0, 0);

      // initialize Leaflet
        function displayMap(userLon, userLat)
        {
            map.setView({ lon: userLon, lat: userLat }, 2);
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
    }
}
