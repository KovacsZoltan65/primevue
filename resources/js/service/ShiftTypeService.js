import BaseService from "./BaseService";

class CompanyService extends BaseService
{

    constructor()
    {
        super();
    }

    url = '/shift_types';

    getShiftTypes()
    {
        return this.get(this.url);
    }

    getShiftType(id)
    {
        return this.get(`${this.url}/${id}`);
    }

    getShiftTypeByName(name)
    {
        return this.get(`${this.url}/name/${name}`);
    }

    createShiftType(data)
    {
        return this.post(this.url, data);
    }

    updateShiftType(id, data)
    {
        return this.put(`${this.url}/${id}`, data);
    }

    deleteShiftTypes(ids) {
        const query = ids.map(id => `ids[]=${id}`).join('&');
        return this.delete(`${this.url}?${query}`);
    }

    deleteShiftType(id) {
        return this.delete(this.url + `/${id}`);
    }
}
export default new ShiftTypeService();
