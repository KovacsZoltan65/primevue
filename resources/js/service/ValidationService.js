import BaseService from "./BaseService";

class ValidationService extends BaseService {

    constructor() {
        super();
    }

    submitForm() {
        return this.post("/submit-form");
    }

}

export default new ValidationService;
