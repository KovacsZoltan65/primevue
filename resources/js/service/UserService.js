import BaseService from "./BaseService";

class UserService extends BaseService {
    /**
     * UserService konstruktor
     *
     * @constructor
     */
    constructor() {
        /**
         * A szolgáltatás alapértelmezett konfigurációja
         *
         * @type {BaseService}
         */
        super();
    }

    url = "/users";

    /**
     * Szerezd meg az összes felhasználót az API-ból.
     *
     * @returns {Promise<AxiosResponse<User[]>>} Ígéret az API válaszával.
     */
    getUsers() {
        return this.get(this.url);
    }

    /**
     * Szerezd meg a felhasználót az azonosítója alapján az API-ból.
     *
     * @param {number} id - A lekérni kívánt felhasználó azonosítója.
     * @returns {Promise<AxiosResponse<User>>} Ígéret az API válaszával.
     */
    getUser(id) {
        return this.get(`${this.url}/${id}`);
    }

    /**
     * Szerezd meg a felhasználót a neve alapján az API-ból.
     *
     * @param {string} name - A lekérni kívánt felhasználó neve.
     * @returns {Promise<AxiosResponse<User>>} Ígéret az API válaszával.
     */
    getUserByName(name) {
        return this.get(`${this.url}/name/${name}`);
    }

    /**
     * Hozzon létre egy új felhasználót az API-ban.
     *
     * A metódus elküldi a felhasználó adatait a szervernek a POST-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {object} data - Az új felhasználó adatait tartalmazó objektum.
     * @return {Promise<AxiosResponse<any>>}
     */
    createUser(data) {
        return this.post(this.url, data);
    }

    /**
     * Frissítsen egy felhasználót az azonosítója alapján az API-ban.
     *
     * A metódus elküldi a felhasználó frissítési kérést a szervernek a PUT-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A frissítend  felhasználó azonosítója.
     * @param {object} data - A felhasználó frissítend  adatait tartalmazó objektum.
     * @return {Promise<AxiosResponse<any>>}
     */
    updateUser(id, data) {
        return this.put(`${this.url}/${id}`, data);
    }

    /**
     * Frissítsen egy felhasználó jelszavát az azonosítója alapján az API-ban.
     *
     * A metódus elküldi a jelszó frissítési kérést a szervernek a PUT-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A frissítend  felhasználó azonosítója.
     * @param {object} data - A felhasználó új jelszavát tartalmazó objektum.
     * @return {Promise<AxiosResponse<any>>}
     */
    updatePassword(id, data) {
        console.log("id", id);
        console.log("data", data);
        //return this.put(`/users/password/${id}`, data);
    }

    /**
     * Töröljön egy felhasználót az azonosítója alapján az API-ból.
     *
     * A metódus elküldi a törlési kérést a szervernek a DELETE-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A törölni kívánt felhasználó azonosítója.
     * @return {Promise<AxiosResponse<any>>}
     */
    deleteUser(id) {
        return this.delete(`${this.url}/${id}`);
    }
}

export default new UserService();
