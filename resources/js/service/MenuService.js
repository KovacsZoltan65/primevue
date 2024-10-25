import BaseService from "./BaseService";

class MenuService extends BaseService {
    constructor() {
        super();
    }
    getMenuItems() {
        return this.get("/menu-items");
    }

    updateMenuUsage(id) {
        return this.put(`/menu-items/${id}/usage`);
    }

    updateMenuItem(id, data) {
        return this.put(`/menu-items/${id}`, data);
    }
}

export default new MenuService();
