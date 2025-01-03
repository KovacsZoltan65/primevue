import BaseService from "./BaseService";
import { v4 as uuidv4 } from "uuid";

class ErrorService extends BaseService {
    constructor() {
        super();
    }

    logClientError(error, additionalData = {}) {
        
        const payload = {
            message: error.message,
            stack: error.stack,
            component: additionalData.componentName || "Unknown",
            category: additionalData.category || "unknown_error",
            priority: additionalData.priority || "low",
            data: additionalData.data || null,
            info: error.info || "No additional info",
            additionalInfo: additionalData.additionalInfo || null, // Külön mezőként kerül mentésre
            time: new Date().toISOString(),
            route: window.location.pathname,
            url: window.location.href,
            userAgent: navigator.userAgent,
            uniqueErrorId: uuidv4(), // Egyedi azonosító generálása kliens oldalon
            ...additionalData,
        };

        return this.post("/client-errors", payload);
        
    }

    logValidationError(error, additionalData = {}) {
        const payload = {
            component: additionalData.componentName || "Unknown",
            additionalInfo: additionalData.additionalInfo || null, // Külön mezőként kerül mentésre
            category: additionalData.category || "Validation Error",
            priority: additionalData.priority || "low",
            validationErrors: Array.isArray(additionalData.validationErrors)
            ? additionalData.validationErrors
            : [],
        };
        return this.post("/client_validation_errors", payload);
    }
}

export default new ErrorService();
