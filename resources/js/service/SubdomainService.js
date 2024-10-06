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
    /**
     * Szerezd meg az összes várost
     *
     * @returns {Promise<AxiosResponse<Subdomain[]>>}
     */
    getSubdomains() {
        return this.get("/subdomains");
    }

    /**
     * Szerezd meg a városok listájának elején lévő 10 elemet.
     *
     * @returns {Promise<AxiosResponse<Subdomain[]>>}
     */
    getSubdomainsSmall() {
        return Promise.resolve(this.getSubdomains().slice(0, 10));
    }

    getSubdomainsMedium() {
        return Promise.resolve(this.getSubdomains().slice(0, 50));
    }

    getSubdomainsLarge() {
        return Promise.resolve(this.getSubdomains().slice(0, 200));
    }

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
        return this.get(`/subdomains/${id}`);
    }

    /**
     * Szerezd meg a várost a neve alapján az API-ból.
     *
     * @param {string} name - A lekérni kívánt város neve.
     * @returns {Promise<AxiosResponse<Subdomain>>}
     */
    getSubdomainByName(name) {
        return this.get(`/subdomains/name/${name}`);
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
        return this.post("/subdomains", data);
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
        return this.put(`/subdomains/${id}`, data);
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
        return this.delete(`/subdomains/${id}`);
    }
}
export default new SubdomainService();
