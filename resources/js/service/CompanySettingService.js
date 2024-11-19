import BaseService from "./BaseService";

class CompanySettingService extends BaseService {
    constructor() {
        super();
    }

    // Céges beállítások lekérése company_id alapján
    getSettings(company_id) {
        return this.get(`/companies/${company_id}/settings`);
    }

    // Céges beállítás lekérdezése company_id és setting_id alapján
    getSetting(company_id, setting_id) {
        return this.get(`/companies/${company_id}/settings/${setting_id}`);
    }

    // Céges beállítás lekérdezése company_id és name szerint
    getSettingByName(company_id, name) {
        return this.get(`/companies/${company_id}/settings/name/${name}`);
    }

    // Céges beállítás létrehozása
    createSetting(company_id, data) {
        return this.post(`/companies/${company_id}/settings`, data);
    }

    // Céges beállítás frissítése
    updateSetting(company_id, setting_id, data) {
        return this.put(`/companies/${company_id}/settings/${setting_id}`, data);
    }

    // Céges beállítás aktiválása / deaktiválása
    activateSetting(company_id, setting_id) {
        return this.patch(`/companies/${company_id}/settings/${setting_id}/toggle-active`);
    }

    // Céges beállítás törlése
    deleteSetting(company_id, setting_id) {
        return this.delete(`/companies/${company_id}/settings/${setting_id}`);
    }
};

export default new CompanySettingService();