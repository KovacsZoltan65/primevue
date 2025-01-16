import BaseService from "./BaseService";

class PersonService extends BaseService {

    /**
     * PersonService konstruktor
     *
     * @constructor
     */
    constructor() {
        /**
         * A szolgáltatások alapértelmezett konfigurációs tulajdonságai.
         *
         * @type {{baseURL: string, endpoints: {getPersons: string}}}
         */
        super();
    }

    url = '/persons';

    /**
     * Lekérdezi az összes személyt az API-ból.
     *
     * @returns {Promise<AxiosResponse<Person[]>>} 
     */
    getPersons() {
        return this.get(this.url);
    }

    /**
     * Lekérdezi a megadott azonosítójú személyt az API-ból.
     *
     * @param {number} id - A lekérni kívánt személy azonosítója.
     * @returns {Promise<AxiosResponse<Person>>} Ígéret az API válaszával.
     */
    getPerson(id) {
        return this.get(this.url + `/${id}`);
    }

    /**
     * Lekérdezi a megadott névvel rendelkez  személyt az API-ból.
     *
     * @param {string} name - A lekérni kívánt személy neve.
     * @returns {Promise<AxiosResponse<Person>>} Ígéret az API válaszával.
     */
    getPersonByName(name) {
        return this.get(this.url + `/name/${name}`);
    }

    /**
     * Hozzon létre egy új személyt az API-ban.
     *
     * Elküldi a személy adatait a szervernek a POST-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {object} data - Az új személy adatait tartalmazó objektum.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    createPerson(data) {
        return this.post(this.url, data);
    }

    /**
     * Frissítsen egy személyt az azonosítója alapján az API-ban.
     *
     * Elküldi a személy adatait a szervernek a PUT-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A frissítend  személy azonosítója.
     * @param {object} data - A személy adatai.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    updatePerson(id, data) {
        return this.put(this.url + `/${id}`, data);
    }

    /**
     * Töröljön egy személyt az API-ból az azonosítója alapján.
     *
     * Elküldi a törlési kérést a szervernek a DELETE-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A törölni kívánt személy azonosítója.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    deletePerson(id) {
        return this.delete(this.url + `/${id}`);
    }
}

export default new PersonService();