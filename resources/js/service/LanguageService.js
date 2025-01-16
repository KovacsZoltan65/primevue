import BaseService from "./BaseService";

class LanguageService extends BaseService {
    constructor() {
        super();
    }

    url = '/languages';

    getLanguages() {
        return this.get(this.url);
    }
}
export default new LanguageService();
