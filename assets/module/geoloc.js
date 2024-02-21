export function geolocationError()
{
    document.location.href = '/search'
}

export function displayMap(L, map)
{
    map.setZoom(15);
    // add the OpenStreetMap tiles
    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution:
        '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>',
    }).addTo(map);

    // show the scale bar on the lower left corner
    L.control.scale({ imperial: true, metric: true }).addTo(map);
}

export function getTerminals(L, map, longitude, latitude)
{
    const terminals = fetch('/getterminal/' + longitude + '/' + latitude + '/10000')
        .then((resp) => {return resp.json()})
        .then((data) => displayTerminals(data, L, map));
}

export function displayTerminals(data, L, map)
{
    const pathname = window.location.pathname;
    data.forEach(elt => {
        let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon',
            html: '<div aria-label="' + elt.address + '">' + elt.address + '</div>'})
        let marker = L.marker([elt.latitude, elt.longitude], {icon: terminalIcon}).addTo(map);
        const url = '/booking/register/' + elt.id;
        marker.bindPopup(' <br> ' + elt.address + ' <br> ' + '<a href="' +
      url +
      '"><button class="button" data-terminal-id="' + elt.id + '">Reservation</button></a>');
    });
}
