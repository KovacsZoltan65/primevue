import BaseService from "./BaseService";

class EntityService extends BaseService {

    constructor() {
        super();
    }

    getEntities() {
        return this.get("/entities");
    }

    getEntity(id) {
        return this.get(`/entities/${id}`);
    }

    getEntityByName(name) {
        return this.get(`/entities/name/${name}`);
    }

    createEntity(data) {
        return this.post("/entities", data);
    }

    updateEntity(id, data) {
        return this.put(`/entities/${id}`, data);
    }

    deleteEntity(id) {
        return this.delete(`/entities/${id}`);
    }
}

export default new EntityService();