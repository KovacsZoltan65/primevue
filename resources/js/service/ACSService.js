import BaseService from "./BaseService";

class ACSService extends BaseService {
    constructor() {
        super();
    }

    getACSs() {
        return this.get("/acs_systems");
    }

    getACS(id) {
        return this.get(`/acs_systems/${id}`);
    }

    getACSByName(name) {
        return this.get(`/acs_systems/name/${name}`);
    }
    
    createACS(data) {
        return this.post(`/acs_systems`, data);
    }
    
    updateACS(id, data) {
        return this.put(`/acs_systems/${id}`, data);
    }
    
    deleteACSs(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`/acs_systems?${query}`);
    }

    deleteACS() {
        return this.delete(`/acs_system/${id}`);
    }
}

export default new ACSService();