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

    /**
     * Szerezd meg az összes entitást.
     *
     * @returns {Promise<AxiosResponse<Entity[]>>} Ígéret az API válaszával.
     */
    getEntities() {
        return this.get("/entities");
    }


    /**
     * Szerezd meg az adott ID-jú entitást.
     *
     * @param {number} id Az entitás ID-ja.
     *
     * @returns {Promise<AxiosResponse<Entity>>} Ígéret az API válaszával.
     */
    getEntity(id) {
        return this.get(`/entities/${id}`);
    }


    /**
     * Szerezd meg az adott névvel rendelkező entitást.
     *
     * @param {string} name Az entitás neve.
     *
     * @returns {Promise<AxiosResponse<Entity>>} Ígéret az API válaszával.
     */
    getEntityByName(name) {
        return this.get(`/entities/name/${name}`);
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
        return this.post("/entities", data);
    }

    /**
     * Frissítsen egy entitást az API-ban az azonosítója alapján.
     *
     * Elküldi az entitás adatait a szervernek a PUT-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A frissítend  entitás azonosítója.
     * @param {object} data - Az új entitás adatait tartalmazó objektum.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    updateEntity(id, data) {
        return this.put(`/entities/${id}`, data);
    }

    /**
     * Töröljön egy entitást az API-ból az azonosítója alapján.
     *
     * Elküldi a törlési kérést a szervernek a DELETE-kérésben,
     * és az API válaszát visszaadja.
     *
     * @param {number} id - A törölni kívánt entitás azonosítója.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    deleteEntity(id) {
        return this.delete(`/entities/${id}`);
    }
}

export default new EntityService();