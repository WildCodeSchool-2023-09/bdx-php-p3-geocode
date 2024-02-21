import { Controller } from '@hotwired/stimulus';
import {displayMap} from "../module/geoloc";

export default class extends Controller {
    static targets =  ['target_name'];

    connect()
    {
        let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon'})
        let map = L.map("map").setView({ lon: longitude, lat: latitude }, 15);
        map.setMinZoom(3);
        //map.setZoom(15);
        L.marker([latitude, longitude ], {icon: terminalIcon})
        .bindPopup("plop")
        .addTo(map);

        displayMap(L, map);
    }
}
