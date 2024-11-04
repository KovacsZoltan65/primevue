import BaseService from "./BaseService";

class ErrorService extends BaseService {
    /**
     * Létrehoz egy új CityService példányt.
     * Meghívja a szülő osztály konstruktorát.
     */
    constructor() {
        /**
         * A szolgáltatások alapértelmezett konfigurációs tulajdonságai.
         *
         * @type {{baseURL: string, endpoints: {getLogs: string}}}
         */
        super();
    }

    getLogs() {
        return this.get('/logs');
    }

    getLogByDate(date) {
        return this.get(`/logs/${date}`);
    }

    getLogsByDateInterval(startDate, endDate) {
        return this.get(`/logs/${startDate}/${endDate}`);
    }

    createLog(data) {
        return this.post("/logs", data);
    }
}

export default new ErrorService();