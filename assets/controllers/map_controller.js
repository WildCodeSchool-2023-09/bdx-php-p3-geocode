import { Controller } from '@hotwired/stimulus';
import { getTerminals, geolocationError, displayMap } from '../module/geoloc';

export default class extends Controller {
    connect()
    {
        let userLat = 0;
        let userLon = 0;
        let map = L.map("map");
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
                getTerminals(L, map, userLon, userLat);
            },
            geolocationError);
        } else {
            // eslint-disable-next-line no-console
            console.log("geolocation IS NOT available");
        }
        displayMap(L, map);
    }

}
