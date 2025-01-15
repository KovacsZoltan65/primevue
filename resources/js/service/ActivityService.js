import BaseService from "./BaseService";

class ActivityService extends BaseService {
    constructor() {
        super();
    }

    url = '/activities';

    getActivities() {
        console.log(this.url);
        return this.get(this.url);
    }
};

export default new ActivityService();