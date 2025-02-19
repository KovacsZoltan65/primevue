import BaseService from "./BaseService";

class EntityService extends BaseService {

    /**
     * Az EntityService konstruktora.
     *
     * Inicializálja az osztályt a szükséges beállításokkal.
     */
    constructor() {
        super();
    }

    url = "/entities";
    /**
     * Szerezd meg az összes entitást.
     *
     * @returns {Promise<AxiosResponse<Entity[]>>} Ígéret az API válaszával.
     */
    getEntities() {
        return this.get(this.url);
    }


    /**
     * Szerezd meg az adott ID-jú entitást.
     *
     * @param {number} id Az entitás ID-ja.
     *
     * @returns {Promise<AxiosResponse<Entity>>} Ígéret az API válaszával.
     */
    getEntity(id) {
        return this.get(`${this.url}/${id}`);
    }


    /**
     * Szerezd meg az adott névvel rendelkező entitást.
     *
     * @param {string} name Az entitás neve.
     *
     * @returns {Promise<AxiosResponse<Entity>>} Ígéret az API válaszával.
     */
    getEntityByName(name) {
        return this.get(`${this.url}/name/${name}`);
    }


    /**
     * Hozzon létre egy új entitást az API-ban.
     *
     * Elküldi az entitás adatait a szervernek a POST-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {object} data Az új entitás adatait tartalmazó objektum.
     *
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    createEntity(data) {
        return this.post(this.url, data);
    }

    updateEntity(id, data) {
        return this.put(`${this.url}/${id}`, data);
    }

    deleteEntity(id) {
        return this.delete(`${this.url}/${id}`);
    }

    deleteEntities(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`${this.url}?${query}`);
    }
}

export default new EntityService();
