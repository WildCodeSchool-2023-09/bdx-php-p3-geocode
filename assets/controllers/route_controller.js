import {Controller} from '@hotwired/stimulus';
import {displayDataMap} from "../module/geoloc";

export default class extends Controller {
    static targets =  ['target_name'];

    connect()
    {
        const stepLength = Number(this.element.dataset.step);
        const map = L.map('map');
      // create map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        map.setView({ lon: 0, lat: 45 }, 12);

      //create route
        let control = L.Routing.control({
            waypoints: [
            L.latLng(this.element.dataset.departureLatitude, this.element.dataset.departureLongitude),
            L.latLng(this.element.dataset.arrivalLatitude, this.element.dataset.arrivalLongitude)
            ],
            routeWhileDragging: true,
            show: true,
            lineOptions : {
                styles: [{color: 'red', opacity: 1, weight: 10}]
            }
        });
        control.addTo(map);
        control._pendingRequest.onloadend = function () {
            const resp = fetch('/api/route', {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ "points": JSON.stringify(control._selectedRoute.coordinates), 'step': stepLength })
            })
            .then(response => response.json())
            .then((data) => displayDataMap(data, L, map))
            .catch((err) => console.error(err));
        }
    }
}
