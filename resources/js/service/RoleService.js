import BaseService from "./BaseService";

class RoleService extends BaseService {

    constructor() {
        super();
    }

    getRoles() {
        return this.get(`/roles`);
    }

    getRole(id) {
        return this.get(`/roles/${id}`);
    }

    getRoleByName(name) {
        return this.get(`/roles/name/${name}`);
    }

    createRole(data) {
        return this.post(`/roles`, data);
    }

    updateRole(id, data) {
        return this.put(`/roles/${id}`, data);
    }

    deleteRole(id) {
        return this.delete(`/roles/${id}`);
    }
}

export default new RoleService;