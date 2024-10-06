import BaseService from "./BaseService";

class ValidationService extends BaseService {

    constructor() {
        super();
    }

    submitForm(data) {
        console.log('service',data);
        return this.post("/submit-form", data);
    }

}

export default new ValidationService;
