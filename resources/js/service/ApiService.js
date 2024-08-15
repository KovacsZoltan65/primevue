import axios from "axios";

// Axios kliens konfigurálása
const apiClient = axios.create({
    baseURL: 'api', // Állítsd be a megfelelő API bázis URL-t
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    withCredentials: true,  // Ha szükséges, például ha sütiket használsz
});

// Központosított hiba- és jogosultságkezelés
apiClient.interceptors.response.use(
    response => response,
    error => {
        if(error.response && error.response.status === 401){
            // Például: kezelheted az autentikációs hibákat itt
            console.error('Unauthorized! Redirecting to login...');
        }
        return Promise.reject(error);
    }
);

// API hívások
export default {
    
    // Például egy GET kérés
    getItems(){
        return apiClient.get('/items');
    },

    // Például egy POST kérés
    createItems(data){
        return apiClient.post('/items', data);
    },
    
    // Például egy PUT kérés
    updateItem(id, data) {
        return apiClient.put(`/items/${id}`, data);
    },

    // Például egy DELETE kérés
    deleteItems(id){
        return apiClient.delete(`/items/${id}`);
    },
};