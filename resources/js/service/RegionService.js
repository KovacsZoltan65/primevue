const { default: BaseService } = require("./BaseService")

class RegionService extends BaseService {
    constructor() {
        super();
    }

    getRegions() {
        return this.get("/regions")
    }

    getRegion() {
        return this.get(`/region${id}`)
    }

    getRegionByName() {
        return this.get(`/regions/name/${name}`)
    }

    createRegion() {
        return this.post("/regions", data);
    }

    updateRegion() {
        return this.put(`/regions/${id}`, data);
    }

    deleteRegion() {
        return this.delete(`/regions/${id}`);
    }
}
export default new RegionService();