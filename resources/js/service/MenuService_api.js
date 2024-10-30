import BaseService from "./BaseService";

class MenuService extends BaseService {
    constructor() {
        super();
    }
    getMenuItems() {
        return this.get("/menu-items");
    }

    getMenuItem(id) {
        return this.get(`/menu-items/${id}`);
    }

    createMenuItem(data) {
        return this.post("/menu-items", data);
    }

    updateMenuItem(id, data) {
        return this.put(`/menu-items/${id}`, data);
    }

    deleteMenuItem(id) {
        return this.delete(`/menu-items/${id}`);
    }

    updateMenuUsage(id) {
        return this.put(`/menu-items/${id}/usage`);
    }
}

export default new MenuService();
