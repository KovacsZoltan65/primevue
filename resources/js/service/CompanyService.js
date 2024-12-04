import BaseService from "./BaseService";

class CompanyService extends BaseService {
    /**
     * Létrehoz egy új CompanyService példányt.
     * Meghívja a szülő osztály konstruktorát.
     */
    constructor() {
        super();
    }

    /**
     * Szerezd meg az összes céget
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */

    getCompanies() {
        return this.get("/companies");
    }

    /**
     * Szerezd meg egy cég adatait az API-ból.
     * @param {number} id - A lekérni kívánt cég azonosítója.
     * @return {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    getCompany(id) {
        return this.get(`/companies/${id}`);
    }

    /**
     * Szerezd meg egy cég adatait a neve alapján az API-ból.
     * @param {string} name - A lekérni kívánt cég neve.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    getCompanyByName(name) {
        return this.get(`/companies/name/${name}`);
    }

    /**
     * Hozzon létre egy új céget az API-ban.
     * Elküldi a cég adatait a szervernek a POST-kérésben,
     * és az API válaszát visszaadja.
     * @param {object} data - Az új cég adatai.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    createCompany(data) {
        return this.post(`/companies`, data);
    }

    /**
     * Frissítsen egy céget az azonosítója alapján az API-ban.
     * Elküldi a cég adatait a szervernek a PUT-kérésben,
     * és az API válaszát visszaadja.
     * @param {number} id - A frissítend  cég azonosítója.
     * @param {object} data - A cég adatai.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    updateCompany(id, data) {
        return this.put(`/companies/${id}`, data);
    }

    /**
     * Töröljön egy vagy több céget az azonosítóik alapján az API-ból.
     * Elküldi a törlési kérést a szervernek a DELETE-kérésben,
     * és az API válaszát visszaadja.
     * @param {number[]} ids - A törölni kívánt cégek azonosítói.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    deleteCompanies(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`/companies?${query}`);
    }

    /**
     * Töröljön egy céget az azonosítója alapján az API-ból.
     * Elküldi a törlési kérést a szervernek a DELETE-kérésben,
     * és az API válaszát visszaadja.
     * @param {number} id - A törölni kívánt cég azonosítója.
     * @returns {Promise<AxiosResponse<any>>} Ígéret az API válaszával.
     */
    deleteCompany(id) {
        return this.delete(`/companies/${id}`);
    }
}

export default new CompanyService();
