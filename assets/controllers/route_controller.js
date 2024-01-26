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
        const map = L.map('map');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        map.setView({ lon: 0, lat: 45 }, 12);

        // var control = L.Routing.control({
        //     waypoints: [
        //     L.latLng(45, 0),
        //     L.latLng(45, 1)
        //     ],
        //     routeWhileDragging: true,
        //     show: true,
        //     lineOptions : {
        //         styles: [{color: 'white', opacity: 1, weight: 20}]
        //     }
        // });
        //
        // control.addTo(map);
        //
        // control._pendingRequest.onloadend = function () {
        //   //TODO
        //   console.log(control._selectedRoute.coordinates, "TERMINEE !!! ")
        // }

        let latlngs = [
          {lon : -0.1, lat : 45.51},
          {lon : -0.43, lat : 45.77},
          {lon : -0.52, lat : 45.88}
        ];
        function onMapClick(e)
        {
            alert("You clicked the map at " + e.latlng);
        }

        map.on('click', onMapClick);

        var circle = L.circle([0, 45], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 500
        }).addTo(map);
        console.log(circle);

        L.marker({ lon: 0, lat: 44.9 })
        .bindPopup("Vous êtes ici")
        .addTo(map);

        var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);
        L.marker([45,0]).addTo(map);
// zoom the map to the polyline
       // map.fitBounds(polyline.getBounds());
        latlngs = [
        [45.51, -122.68],
        [37.77, -122.43],
        [34.04, -118.2]
        ];

        var polyline = L.polyline(latlngs, {color: 'red'}).addTo(map);

// zoom the map to the polyline
        map.fitBounds(polyline.getBounds());

    }
}
