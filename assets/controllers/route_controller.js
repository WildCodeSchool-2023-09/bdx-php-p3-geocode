import {Controller} from '@hotwired/stimulus';
import {Point} from "../module/Point";
import {findAllSteps, findNextStep} from "../module/geoloc";


export default class extends Controller {
    static targets =  ['target_name'];

    connect()
    {
        console.log('map connect');
        this.displayMap();
    }

    displayMap()
    {
        console.log(this.element.dataset);
        const stepLength = Number(this.element.dataset.step);
        let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon'})
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
            const steps = findAllSteps(control._selectedRoute.coordinates, stepLength);
            steps.forEach((step) => getTerminals(step))
        }

        function getTerminals(step)
        {
            const host = window.location.host;
            const protocol = window.location.protocol;
            const url = protocol + host + '/getterminal/'
            const terminals = fetch('https://localhost:8000/getterminal/' + step.longitude + '/' + step.latitude)
            .then((resp) => {return resp.json()})
            .then((data) => displayDataMap(data))
            .catch((err) => console.error(err));
        }

        function displayDataMap(data)
        {
            const host = window.location.host;

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
