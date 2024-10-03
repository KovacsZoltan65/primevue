
import BaseService from "./BaseService";

class LanguageService extends BaseService
{
    constructor() {
        super();
    }

    getLanguages() {
        return this.get('/languages');
    }

}
export default new LanguageService();

/*
export const LanguageService = {
    getLanguageData() {
        return [
            { name: "United States", code: "US" },
            { name: "Magyarország", code: "HU" },
            { name: "Great Britain", code: "GB" },
        ];
    },
    getLanguages() {
        return Promise.resolve(this.getLanguageData());
    },

    setLocale() {
        //
    }
};
*/
