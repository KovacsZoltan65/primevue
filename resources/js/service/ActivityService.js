import BaseService from "./BaseService";

class ActivityService extends BaseService {
    constructor() {
        super();
    }

    url = '/activities';

    getActivities() {
        return this.get(this.url);
    }

    getActivity(id) {
        return this.get(`${this.url}/${id}`);
    }

    getActivityByLogName(name) {
        return this.get(`${this.url}/name/${name}`);
    }
};

export default new ActivityService();