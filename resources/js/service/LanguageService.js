import BaseService from "./BaseService";

class LanguageService extends BaseService {
    constructor() {
        super();
    }

    getLanguages() {
        return this.get("/languages");
    }
}
export default new LanguageService();
