/**
 *  Use this if geolocation isn't authorized by user
 */
export function geolocationError()
{
    document.location.href = '/search'
}

export function displayMap(L, map)
{
    // add the OpenStreetMap tiles
    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        // add copyright. It's in Leaflet terms of use
        attribution:
        '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>',
    }).addTo(map);

    // show the scale bar in the lower left corner
    L.control.scale({ imperial: true, metric: true }).addTo(map);
}

/**
 *  get all terminals around a position and displays them
 *
 * @param L Leaflet object
 * @param map map where terminals will be displayed
 * @param longitude
 * @param latitude
 */
export function getTerminals(L, map, longitude, latitude)
{
    const terminals = fetch('/getterminal/' + longitude + '/' + latitude + '/10000')
        .then((resp) => {return resp.json()})
        .then((data) => displayTerminals(data, L, map));
}

/**
 * @param terminals all terminals position to be displayed
 * @param L Leaflet Object
 * @param map Map map where terminals will be displayed
 */
export function displayTerminals(terminals, L, map)
{
    terminals.forEach(terminal => {
        let terminalIcon = L.divIcon({iconSize:[32, 32], className: 'map-terminal-icon',
            //html here is to use keyboard navigation
            html: '<div aria-label="Borne électrique ' + terminal.address + '">Borne électrique '
              + terminal.address + '</div>'})
        let marker = L.marker([terminal.latitude, terminal.longitude], {icon: terminalIcon}).addTo(map);
        const url = '/booking/register/' + terminal.id;
        marker.bindPopup(' <br> ' + terminal.address + ' <br> ' + '<a href="' +
      url +
      '"><button class="button" data-terminal-id="' + terminal.id + '">Reservation</button></a>');
    });
}
