export const CityService = {
    /**
     * Visszaadja a városok adatait egy tömbben.
     * 
     * @returns {Array} A városok adatai.
     */
    getCitiesData() {
        return [
            {
                // Győr
                id: 958977,
                region_id: 1562,
                country_id: 92,
                latitude: 47.68333330,
                longitude: 17.63333330,
                name: 'Győr',
            },
            {
                // Budapest
                id: 955428,
                region_id: 1555,
                country_id: 92,
                latitude: 47.50000000,
                longitude: 19.08333330,
                name: 'Budapest',
            },
            {
                // Kecskemét
                id: 961048,
                region_id: 1548,
                country_id: 92,
                latitude: 46.90000000,
                longitude: 19.78333330,
                name: 'Kecskemét',
            },
            {
                // Kecskemét (másik)
                id: 961049,
                region_id: 1545,
                country_id: 92,
                latitude: 47.68333330,
                longitude: 17.78333330,
                name: 'Kecskemét',
            },
            {
                // Székesfehérvár
                id: 968570,
                region_id: 1539,
                country_id: 92,
                latitude: 47.20000000,
                longitude: 18.41666670,
                name: 'Székesfehérvár',
            },
        ];
    },

    getCityById(cityId) {
        return Promise.resolve(
            this.getCitiesData().find(city => city.id === cityId)
        );
    },

};