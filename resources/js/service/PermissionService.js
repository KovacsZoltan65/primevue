import BaseService from "./BaseService";

class PermissionService extends BaseService {
    constructor() {
        super();
    }

    url = '/permissions';

    getPermissions() {
        return this.get(this.url);
    }

    getPermission(id) {
        return this.get(this.url + `/${id}`);
    }

    getPermissionByName(name) {
        return this.get(this.url + `/name/${name}`);
    }

    createPermission(data) {
        return this.post(this.url, data);
    }

    updatePermission(id, data) {
        return this.put(this.url + `/${id}`, data);
    }

    deletePermission(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(this.url + `?${query}`);
    }

    deletePermission(id) {
        return this.delete(this.url + `/${id}`);
    }
};

export default new PermissionService();