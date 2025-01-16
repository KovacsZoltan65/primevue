import BaseService from "./BaseService";

class CountryService extends BaseService {
    /**
     * Létrehoz egy új CountryService példányt.
     * Meghívja a szülő osztály konstruktorát.
     */
    constructor() {
        super();
    }

    url = '/countries';

    /**
     * Szerezd meg az összes országot.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    getCountries() {
        return this.get(this.url);
    }

    /**
     * Szerezzen országot azonosítóval.
     * @param {number} id - Az ország azonosítója.
     * @returns {Promise<AxiosResponse<any>>}
     */
    getCountry(id) {
        return this.get(this.url + `/${id}`);
    }

    /**
     * Szerezzen országot név szerint.
     * @param {string} name - Az ország neve.
     * @returns {Promise<AxiosResponse<any>>}
     */
    getCountryByName(name) {
        return this.get(this.url + `/name/${name}`);
    }

    /**
     * Hozzon létre egy új országot.
     * @param {object} data - Az ország adatai.
     * @returns {Promise<AxiosResponse<any>>}
     */
    createCountry(data) {
        return this.post(this.url, data);
    }

    /**
     * Frissítsen egy országot.
     * @param {number} id - Az ország azonosítója.
     * @param {object} data - Az ország adatai.
     * @returns {Promise<AxiosResponse<any>>}
     */
    updateCountry(id, data) {
        return this.put(this.url + `/${id}`, data);
    }

    /**
     * Töröljön egy országot.
     * @param {number} id - Az ország azonosítója.
     * @returns {Promise<AxiosResponse<any>>}
     */
    deleteCountry(id) {
        return this.delete(this.url + `/${id}`);
    }
}

export default new CountryService();
