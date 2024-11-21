import BaseService from "./BaseService";

class ErrorService extends BaseService {
    constructor() {
        super();
    }

    logClientError(error) {
        return this.post("/client-errors", error);
    }
}

export default new ErrorService();