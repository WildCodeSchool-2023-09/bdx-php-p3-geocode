import {degToRad} from "./geoloc";

/**
 *
 * @param longitude or array [longitude, latitude] or {longitude: n, latitude: m} or {lng: n, lat: m}
 * @param latitude
 */
export class Point{
    constructor(longitude, latitude = undefined)
    {
        if (latitude === undefined ) {
            if (Array.isArray(longitude) && longitude.length === 2) {
                this.longitude = longitude[0];
                this.latitude = longitude[1];
            } else if (typeof longitude && longitude.longitude && longitude.latitude) {
                this.longitude = longitude.longitude;
                this.latitude = longitude.latitude;
            } else if (typeof longitude && longitude.lng && longitude.lat) {
                this.longitude = longitude.lng;
                this.latitude = longitude.lat;
            }
        } else {
            this.longitude = longitude;
            this.latitude = latitude;
        }

        if (isNaN(this.longitude)) {
            throw new Error('longitude is not a number!');
        }
        if (isNaN(this.latitude)) {
            throw new Error('latitude is not a number!');
        }
    }

    /**
     * distance between this point and another Point
     *
     * @param anotherPoint Point or something that can be Point (see Point)
     * @returns {number} distance in kilometers
     */
    calcDistanceWith(anotherPoint)
    {
        if (!(anotherPoint instanceof Point)) {
            anotherPoint = new Point(anotherPoint);
        }

        const R = 6371; // km
        const differenceLatitude = degToRad(anotherPoint.latitude - this.latitude);
        const differenceLongitude = degToRad(anotherPoint.longitude - this.longitude);
        const latitude = degToRad(this.latitude);
        const longitude = degToRad(this.longitude);

        let a = Math.sin(differenceLatitude / 2) * Math.sin(differenceLatitude / 2) +
        Math.sin(differenceLongitude / 2) * Math.sin(differenceLongitude / 2) * Math.cos(latitude) * Math.cos(longitude);
        let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    isEqual(anotherPoint)
    {
        return this.latitude === anotherPoint.latitude && this.longitude === anotherPoint.longitude;
    }
}
