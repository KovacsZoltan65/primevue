import BaseService from "./BaseService";

class PersonService extends BaseService {

    constructor() {
        super();
    }

    getPersons() {
        return this.get("/persons");
    }

    getPerson(id) {
        return this.get(`/persons/${id}`);
    }

    getPersonByName(name) {
        return this.get(`/persons/name/${name}`);
    }

    createPerson(data) {
        return this.post("/persons", data);
    }

    updatePerson(id, data) {
        return this.put(`/persons/${id}`, data);
    }

    deletePerson(id) {
        return this.delete(`/persons/${id}`);
    }
}

export default new PersonService();