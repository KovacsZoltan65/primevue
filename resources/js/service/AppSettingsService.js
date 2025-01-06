import BaseService from "./BaseService";

class AppSettingsService extends BaseService {
    constructor() {
        super();
    }

    getSettings() {
        return this.get('app_settings');
    }

    getSetting(id) {
        return this.get(`/app_settings/${id}`);
    }

    getSettingByKey(key) {
        return this.get(`/app_settings/key/${key}`);
    }

    createSetting(data) {
        return this.post(`/app_settings`, data);
    }

    updateSetting(id, data) {
        console.log('id', id);
        console.log('data', data);
        return this.put(`/app_settings/${id}`, data);
    }

    deleteSettings(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`/app_settings?${query}`);
    }

    deleteSetting(id) {
        return this.delete(`/app_settings/${id}`);
    }
}

export default new AppSettingsService();
