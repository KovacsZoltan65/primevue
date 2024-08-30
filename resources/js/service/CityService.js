import axios from 'axios';

// Axios kliens konfigurálása
const apiClient = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true, // Ha szükséges, például ha sütiket használsz
});

apiClient.interceptors.response.use(
    response => response, // A sikeres válaszokat változtatás nélkül továbbítjuk
    error => {
        // Ellenőrizzük, hogy a válasz létezik-e (pl. ha a szerver nem válaszol, akkor nincs válasz objektum)
        if (error.response) {
            // Általános hibakezelés különböző HTTP státuszkódokhoz
            switch (error.response.status) {
                case 400:
                    console.error('Bad Request: ', error.response.data.message || 'Invalid request');
                    break;
                case 401:
                    console.error('Unauthorized! Redirecting to login...');
                    // Itt pl. átirányíthatod a felhasználót a bejelentkezési oldalra
                    window.location.href = '/login';
                    break;
                case 403:
                    console.error('Forbidden: ', error.response.data.message || 'You do not have permission to perform this action');
                    break;
                case 404:
                    console.error('Not Found: ', error.response.data.message || 'The requested resource was not found');
                    break;
                case 500:
                    console.error('Server Error: ', error.response.data.message || 'An internal server error occurred');
                    break;
                default:
                    console.error('Error: ', error.response.data.message || 'An error occurred');
            }
        } else if (error.request) {
            // Ha a kérés elkészült, de a szerver nem válaszolt
            console.error('No response received from server:', error.request);
        } else {
            // Ha a hiba a kérés beállítása során történt
            console.error('Error setting up request:', error.message);
        }

        return Promise.reject(error); // A hibát továbbítjuk, hogy a komponens szinten is kezelhető legyen
    }
);

// API hívások
export default {
    getCities() {
        return apiClient.get('/cities');
    },
    getCityById(id) {
        return apiClient.get('/cities/' + id);
    },
    store(city) {
        return apiClient.post('/cities', city);
    },
    update(id, city) {
        return apiClient.put(`/cities/${id}`, city);
    },
    delete(id) {
        return apiClient.delete(`/cities/${id}`);
    },
    restore(id) {
        return apiClient.put(`/cities/${id}/restore`);
    },
};


//export const CityService = {
    /**
     * Visszaadja a városok adatait egy tömbben.
     * 
     * @returns {Array} A városok adatai.
     */
//    getCitiesData() {
        /*
        return [
            { id: 958977, region_id: 1562, country_id: 92, latitude: 47.68333330, longitude: 17.63333330, name: 'Győr', },
            { id: 955428, region_id: 1555, country_id: 92, latitude: 47.50000000, longitude: 19.08333330, name: 'Budapest', },
            { id: 961048, region_id: 1548, country_id: 92, latitude: 46.90000000, longitude: 19.78333330, name: 'Kecskemét', },
            { id: 961049, region_id: 1545, country_id: 92, latitude: 47.68333330, longitude: 17.78333330, name: 'Kecskemét', },
            { id: 968570, region_id: 1539, country_id: 92, latitude: 47.20000000, longitude: 18.41666670, name: 'Székesfehérvár', },
        ];
        */
//    },

//    getCityById(id) {
        
        /*
        return Promise.resolve(
            this.getCitiesData().find(city => city.id === id)
        );
        */
    //},
//};