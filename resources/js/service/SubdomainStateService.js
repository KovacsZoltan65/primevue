import BaseService from "./BaseService";

class SubdomainStateService extends BaseService {
    /**
     * Létrehoz egy új SubdomainStateService példányt.
     * Meghívja a szülő osztály konstruktorát.
     */
    constructor() {
        /**
         * A szolgáltatások alapértelmezett konfigurációs tulajdonságai.
         *
         * @type {{baseURL: string, endpoints: {getSubdomainStates: string}}}
         */
        super();
    }

    url = '/subdomain_states';

    /**
     * Szerezd meg az összes várost
     *
     * @returns {Promise<AxiosResponse<SubdomainState[]>>}
     */
    getSubdomainStates() {
        return this.get(this.url);
    }

    /**
     * Szerezd meg a várost azonosítója alapján az API-ból.
     *
     * @param {number} id - A lekérni kívánt város azonosítója.
     * @return {Promise<AxiosResponse<SubdomainState>>}
     */
    getSubdomainState(id) {
        return this.get(`${this.url}/${id}`);
    }

    /**
     * Szerezd meg a várost a neve alapján az API-ból.
     *
     * @param {string} name - A lekérni kívánt város neve.
     * @returns {Promise<AxiosResponse<SubdomainState>>}
     */
    getSubdomainStateByName(name) {
        return this.get(`${this.url}/name/${name}`);
    }
    /**
     * Hozzon létre új várost az API-ban.
     *
     * A metódus elküldi a város adatait a szervernek a POST-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {object} data - Új város adatai.
     * @return {Promise<AxiosResponse<SubdomainState>>}
     */
    createSubdomainState(data) {
        return this.post(this.url, data);
    }

    /**
     * Frissítsen egy várost az azonosítója alapján az API-ban.
     *
     * A metódus elküldi a város adatait a szervernek a PUT-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A frissítend  város azonosítója.
     * @param {object} data - A város adatai.
     * @return {Promise<AxiosResponse<SubdomainState>>}
     */
    updateSubdomainState(id, data) {
        return this.put(`${this.url}/${id}`, data);
    }

    /**
     * Töröljön egy várost az azonosítója alapján az API-ból.
     *
     * A metódus elküldi a törlési kérést a szervernek a DELETE-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A törölni kívánt város azonosítója.
     * @return {Promise<AxiosResponse<any>>}
     */
    deleteSubdomainState(id) {
        return this.delete(`${this.url}/${id}`);
    }
}
export default new SubdomainStateService();
