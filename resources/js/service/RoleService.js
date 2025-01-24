import BaseService from "./BaseService";

class RoleService extends BaseService {

    constructor() {
        super();
    }

    url = '/roles';

    getRoles() {
        return this.get(this.url);
    }

    getRole(id) {
        return this.get(`${this.url}/${id}`);
    }

    getRoleByName(name) {
        return this.get(`${this.url}/name/${name}`);
    }

    createRole(data) {
        return this.post(this.url, data);
    }

    updateRole(id, data) {
        return this.put(`${this.url}/${id}`, data);
    }

    deleteRole(id) {
        return this.delete(`${this.url}/${id}`);
    }
}

export default new RoleService;