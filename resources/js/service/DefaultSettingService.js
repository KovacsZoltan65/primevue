import BaseService from "./BaseService";

class DefaultSettingService extends BaseService {
    constructor() {
        super();
    }

    // Alap beállítások lekérése
    getSettings() {
        return this.get('/settings');
    }

    // Alap beállítás lekérdezése id alapján
    getSetting(id) {
        return this.get(`/settings/${id}`);
    }

    // Alap beállítás lekérése name alapján
    getSettingByName(name) {
        return this.get(`/settings/name/${name}`);
    }

    // Alapbeállítás létrehozása
    createSetting(data) {
        return this.post('/settings', data);
    }

    // Alapbeállítás frissítése
    updateSetting(id, data) {
        return this.put(`/settings/${id}`, data);
    }

    // Alapbeállítás aktiválása / deaktiválása
    activateSetting(id) {
        return this.patch(`/settings/${id}/toggle-active`);
    }

    // Alapbeállítás törlése
    deleteSetting(id) {
        return this.delete(`/settings/${id}`);
    }
};

export default new DefaultSettingService();