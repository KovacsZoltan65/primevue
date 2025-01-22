import BaseService from "./BaseService";

class AppSettingsService extends BaseService {
    constructor() {
        super();
    }

    url = '/app_settings';

    getSettings() {
        return this.get(this.url);
    }

    getSetting(id) {
        return this.get(`${this.url}/${id}`);
    }

    getSettingByKey(key) {
        return this.get(`${this.url}/key/${key}`);
    }

    createSetting(data) {
        return this.post(this.url, data);
    }

    updateSetting(id, data) {
        return this.put(`${this.url}/${id}`, data);
    }

    deleteSettings(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`${this.url}?${query}`);
    }

    deleteSetting(id) {
        return this.delete(`${this.url}/${id}`);
    }
}

export default new AppSettingsService();
