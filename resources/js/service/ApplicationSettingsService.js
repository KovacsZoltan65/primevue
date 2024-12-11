import BaseService from "./BaseService";

class CompanySettingsService extends BaseService {
    constructor() {
        super();
    }

    getSettings() {
        return this.get('application_settings');
    }

    getSetting(id) {
        return this.get(`/application_settings/${id}`);
    }

    getSettingByKey(key) {
        return this.get(`/application_settings/key/${key}`);
    }

    createSetting(data) {
        return this.post(`/application_settings`, data);
    }

    updateSetting(id, data) {
        return this.put(`/application_settings/${id}`, data);
    }

    deleteSettings(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`/application_settings?${query}`);
    }

    deleteSetting(id) {
        return this.delete(`/application_settings/${id}`);
    }
}

export default new CompanySettingsService();