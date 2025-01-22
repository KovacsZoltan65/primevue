import BaseService from "./BaseService";

class ACSService extends BaseService {
    constructor() {
        super();
    }

    url = '/acs_systems';

    getACSs() {
        return this.get(this.url);
    }

    getACS(id) {
        return this.get(this.url + `/${id}`);
    }

    getACSByName(name) {
        return this.get(`${this.url}/name/${name}`);
    }

    createACS(data) {
        return this.post(this.url, data);
    }

    updateACS(id, data) {
        return this.put(`${this.url}/${id}`, data);
    }

    deleteACSs(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`${this.url}?${query}`);
    }

    deleteACS() {
        return this.delete(`${this.url}/${id}`);
    }
}

export default new ACSService();
