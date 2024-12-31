import BaseService from "./BaseService";

class CompSettingsService extends BaseService {
    constructor() {
        super();
    }

    getSettings() {
        return this.get('comp_settings');
    }

    getSetting(id) {
        return this.get(`/comp_settings/${id}`);
    }

    getSettingByKey(key) {
        return this.get(`/comp_settings/key/${key}`);
    }

    createSetting(data) {
        return this.post(`/comp_settings`, data);
    }

    updateSetting(id, data) {
        return this.put(`/comp_settings/${id}`, data);
    }

    deleteSettings(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`/comp_settings?${query}`);
    }

    deleteSetting(id) {
        return this.delete(`/comp_settings/${id}`);
    }
}

export default new CompSettingsService();
