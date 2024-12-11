import BaseService from "./BaseService";

class CompanySettingsService extends BaseService {
    constructor() {
        super();
    }

    getSettings() {
        return this.get('company_settings');
    }

    getSetting(id) {
        return this.get(`/company_settings/${id}`);
    }

    getSettingByKey(key) {
        return this.get(`/company_settings/key/${key}`);
    }

    createSetting(data) {
        return this.post(`/company_settings`, data);
    }

    updateSetting(id, data) {
        return this.put(`/company_settings/${id}`, data);
    }

    deleteSettings(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`/company_settings?${query}`);
    }

    deleteSetting(id) {
        return this.delete(`/company_settings/${id}`);
    }
}

export default new CompanySettingsService();