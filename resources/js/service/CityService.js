import BaseService from "./BaseService";

class CityService extends BaseService {
    constructor() {
        super();
    }

    getCities() {
        return this.get("/cities");
    }


    getCity(id) {
        return this.get(`/cities/${id}`);
    }

    getCityByName(name) {
        return this.get(`/cities/name/${name}`);
    }

    createCity(data) {
        return this.post("/cities", data);
    }

    updateCity(id, data) {
        return this.put(`/cities/${id}`, data);
    }

    deleteCity(id) {
        return this.delete(`/cities/${id}`);
    }
}
export default new CityService();
