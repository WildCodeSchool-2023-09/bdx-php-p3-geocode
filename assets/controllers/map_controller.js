import { Controller } from '@hotwired/stimulus';
import { getTerminals, geolocationError, displayMap } from '../module/geoloc';

export default class extends Controller {
    connect()
    {
        // variables initialization
        let userLat = 45;
        let userLon = 0;
        let map = L.map("map");
        // if navigator can use geolocation
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition((position) => {
                // if user accept geolocation
                // we get coordinates with 4 digits, an accuracy of 10 meters
                userLat = Number(position.coords.latitude.toFixed(4));
                userLon = Number(position.coords.longitude.toFixed(4));

                //This allows you to avoid having several world maps if you zoom out too much.
                map.setMinZoom(3);
                // This set view on user position, with a zoom of 15
                map.setView({ lon: userLon, lat: userLat }, 15);

                // This creates a marker on user position
                L.marker({ lon: userLon, lat: userLat })
                    .bindPopup("Vous êtes ici")
                    .addTo(map);
                //This gets all terminals positions and icons, around 10 kilometers
                getTerminals(L, map, userLon, userLat);
            },
            // if user doesn't accept geolocation
            geolocationError);
        } else {
            //if navigator can't use geolocation
            // eslint-disable-next-line no-console
            console.log("geolocation IS NOT available");
        }
        // display the map
        displayMap(L, map);
    }
}
