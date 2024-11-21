import BaseService from "./BaseService";

class ErrorService extends BaseService {
    constructor() {
        super();
    }

    logClientError(error, additionalData = {}) {

        const payload = {
            message: error.message,
            stack: error.stack,
            component: error.componentName || "Unknown",
            info: error.info || "No additional info",
            time: new Date().toISOString(),
            route: window.location.pathname,
            url: window.location.href,
            userAgent: navigator.userAgent,
            ...additionalData,
        };

        return this.post("/client-errors", payload);
    }
}

export default new ErrorService();