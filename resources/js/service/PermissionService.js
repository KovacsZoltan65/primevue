import BaseService from "./BaseService";

class PermissionService extends BaseService {
    constructor() {
        super();
    }

    getPermissions() {
        return this.get("/permissions");
    }

    getPermission(id) {
        return this.get(`/permissions/${id}`);
    }

    getPermissionByName(name) {
        return this.get(`/permissions/name/${name}`);
    }

    createPermission(data) {
        return this.post(`/permissions`, data);
    }

    updatePermission(id, data) {
        return this.put(`/permissions/${id}`, data);
    }

    deletePermission(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`/permissions?${query}`);
    }

    deletePermission(id) {
        return this.delete(`/permissions/${id}`);
    }
};

export default new PermissionService();