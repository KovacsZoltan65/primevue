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
        return this.get('/log-errors');
    }

    getLogByDate(date) {
        return this.get(`/log-errors/${date}`);
    }

    getLogsByDateInterval(startDate, endDate) {
        return this.get(`/log-errors/${startDate}/${endDate}`);
    }

    createLog(data) {
        return this.post("/log-errors", data);
    }
}

export default new ErrorService();