import BaseService from "./BaseService";

class SubdomainService extends BaseService {
    /**
     * Létrehoz egy új SubdomainService példányt.
     * Meghívja a szülő osztály konstruktorát.
     */
    constructor() {
        /**
         * A szolgáltatások alapértelmezett konfigurációs tulajdonságai.
         *
         * @type {{baseURL: string, endpoints: {getSubdomains: string}}}
         */
        super();
    }

    url = '/subdomains';

    /**
     * Szerezd meg az összes várost
     *
     * @returns {Promise<AxiosResponse<Subdomain[]>>}
     */
    getSubdomains() {
        return this.get(this.url);
    }

    /**
     * Szerezd meg a városok listájának elején lévő 10 elemet.
     *
     * @returns {Promise<AxiosResponse<Subdomain[]>>}
     */
    getSubdomainsSmall() {
        return Promise.resolve(this.getSubdomains().slice(0, 10));
    }

    /**
     * Szerezd meg a városok listájának elején lévő 50 elemet.
     *
     * @returns {Promise<AxiosResponse<Subdomain[]>>}
     */
    getSubdomainsMedium() {
        return Promise.resolve(this.getSubdomains().slice(0, 50));
    }

    /**
     * Szerezd meg a városok listájának elején lévő 200 elemet.
     *
     * @returns {Promise<AxiosResponse<Subdomain[]>>}
     */
    getSubdomainsLarge() {
        return Promise.resolve(this.getSubdomains().slice(0, 200));
    }

    /**
     * Szerezd meg a városok listájának elején lévő valamennyi elemet.
     *
     * @returns {Promise<AxiosResponse<Subdomain[]>>}
     */
    getSubdomainsXLarge() {
        return Promise.resolve(this.getSubdomains());
    }

    /**
     * Szerezd meg a várost azonosítója alapján az API-ból.
     *
     * @param {number} id - A lekérni kívánt város azonosítója.
     * @return {Promise<AxiosResponse<Subdomain>>}
     */
    getSubdomain(id) {
        return this.get(`${this.url}/${id}`);
    }

    /**
     * Szerezd meg a várost a neve alapján az API-ból.
     *
     * @param {string} name - A lekérni kívánt város neve.
     * @returns {Promise<AxiosResponse<Subdomain>>}
     */
    getSubdomainByName(name) {
        return this.get(`${this.url}/name/${name}`);
    }
    /**
     * Hozzon létre új várost az API-ban.
     *
     * A metódus elküldi a város adatait a szervernek a POST-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {object} data - Új város adatai.
     * @return {Promise<AxiosResponse<Subdomain>>}
     */
    createSubdomain(data) {
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
     * @return {Promise<AxiosResponse<Subdomain>>}
     */
    updateSubdomain(id, data) {
        return this.put(`${this.url}/${id}`, data);
    }

    deleteSubdomains(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`${this.url}?${query}`);
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
    deleteSubdomain(id) {
        return this.delete(`${this.url}/${id}`);
    }

    migration() {};
}
export default new SubdomainService();
