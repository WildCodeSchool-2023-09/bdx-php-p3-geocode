import { Controller } from '@hotwired/stimulus';
import { getTerminals, displayMap} from "../module/geoloc";

export default class extends Controller {
    static targets =  ['target_name'];

    connect()
    {
        let longitude = 0;
        let latitude = 45;
        let map = L.map("map").setView({lon: longitude, lat: latitude}, 15);
        if (this.element.dataset.latitude !== undefined) {
            latitude = this.element.dataset.latitude;
            longitude = this.element.dataset.longitude;
        } else if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition((position) => {
                latitude = Number(position.coords.latitude.toFixed(4));
                longitude = Number(position.coords.longitude.toFixed(4));
                map.setView({lon: longitude, lat: latitude}, 15);
            });
        }
        map.setMinZoom(3);
        map.setView({lon: longitude, lat: latitude}, 15);

      //map.setZoom(15);
        L.marker({lon: longitude, lat: latitude})
        .bindPopup("Vous êtes ici")
        .addTo(map);
        getTerminals(L, map, longitude, latitude);

        displayMap(L, map);
    }
}
