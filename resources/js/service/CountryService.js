import BaseService from "./BaseService";

class CountryService extends BaseService {
    /**
     * Osztályépítő.
     * Meghívja a szülő osztály konstruktorát.
     */
    constructor() {
        super();
    }

    /**
     * Szerezd meg az összes országot.
     * @returns {Promise<AxiosResponse<any>>}
     */
    getCountries() {
        return this.get("/countries");
    }

    /**
     * Szerezzen országot azonosítóval.
     * @param {number} id - Az ország azonosítója.
     * @returns {Promise<AxiosResponse<any>>}
     */
    getCountry(id) {
        return this.get(`/countries/${id}`);
    }

    /**
     * Szerezzen országot név szerint.
     * @param {string} name - Az ország neve.
     * @returns {Promise<AxiosResponse<any>>}
     */
    getCountryByName(name) {
        return this.get(`/countries/name/${name}`);
    }

    /**
     * Hozzon létre egy új országot.
     * @param {object} data - Az ország adatai.
     * @returns {Promise<AxiosResponse<any>>}
     */
    createCountry(data) {
        return this.post("/countries", data);
    }

    /**
     * Frissítsen egy országot.
     * @param {number} id - Az ország azonosítója.
     * @param {object} data - Az ország adatai.
     * @returns {Promise<AxiosResponse<any>>}
     */
    updateCountry(id, data) {
        return this.put(`countries/${id}`, data);
    }

    /**
     * Töröljön egy országot.
     * @param {number} id - Az ország azonosítója.
     * @returns {Promise<AxiosResponse<any>>}
     */
    deleteCountry(id) {
        return this.delete(`countries/${id}`);
    }
}

export default new CountryService();
