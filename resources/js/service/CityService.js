import axios from "axios";
import { trans } from "laravel-vue-i18n";
import { CONFIG } from "@/helpers/constants";

// Axios kliens konfigurálása
const apiClient = axios.create({
    baseURL: CONFIG.BASE_URL, // Állítsd be a megfelelő API bázis URL-t
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    withCredentials: true, // Ha szükséges, például ha sütiket használsz
});

/**
 * Központosított hiba- és jogosultságkezelés
 * Ez a kód blokk az API kliens válaszait kezelő interceptor példája.
 * Ha a szerver hibaüzenetet ad vissza, akkor a kódblokkban definiált függvényekkel kezeljük a hibát.
 * A hibakezelésben a válaszban szereplő HTTP státusz kód alapján különböző hibaüzeneteket jelenítünk meg a konzolon.
 */
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            /**
             * A metódus a válaszban szereplő HTTP státusz kód alapján különböző hibaüzeneteket jelenít meg a konzolon.
             * Ha a válaszban szerepel egy hibaüzenet akkor azt jeleníti meg, különben az alapértelmezett üzenetet jeleníti meg.
             * @param {number} status - A válaszban szereplő HTTP státusz kód.
             * @returns {void}
             */
            switch (error.response.status) {
                // 400 - Bad Request
                case 400:
                    console.error(
                        trans("error_400") + ": ",
                        error.response.data.message || "Invalid request",
                    );
                    break;
                // 401 - Unauthorized
                case 401:
                    console.error(trans("error_401"));
                    // Jelenleg az oldal átirányítja a felhasználót a bejelentkezési oldalra, de itt a kívánt módon kezelheted a hibát
                    window.location.href = "/login";
                    break;
                // 403 - Forbidden
                case 403:
                    console.error(
                        trans("error_403") + ": ",
                        error.response.data.message || "Forbidden",
                    );
                    break;
                // 404 - Not found
                case 404:
                    console.error(
                        trans("error_404") + ": ",
                        error.response.data.message || "Not found",
                    );
                    break;
                // 500 - Internal server error
                case 500:
                    console.error(
                        trans("error_500") + ": ",
                        error.response.data.message || "Internal server error",
                    );
                    break;
                // Végső esetben a válaszban szereplő hibaüzenetet vagy az alapértelmezett üzenetet jeleníti meg
                default:
                    console.error(
                        trans("error_default") + ": ",
                        error.response.data.message || "Unknown error",
                    );
                    break;
            }
        }
        /*
        if(error.response && error.response.status === 401){
            // Például: kezelheted az autentikációs hibákat itt
            console.error('Unauthorized! Redirecting to login...');
        }
        return Promise.reject(error);
        */
    },
);

export default {
    /**
     * Lekéri a városok listáját az API-ból.
     *
     * @return {Promise} Egy ígéret, amely visszatér a városok listájával.
     */
    getCities() {
        return apiClient.get("/cities");
    },

    /**
     * Lekér egy várost az azonosítója alapján az API-ból.
     *
     * @param {number} id - A lekérni kívánt város azonosítója.
     * @return {Promise} Ígéret, ami a város adataival tér vissza.
     */
    getCity(id) {
        return apiClient.get(`/cities/${id}`);
    },

    /**
     * Város keresése a neve alapján.
     *
     * @param {string} name - A visszakeresendő város neve.
     * @return {Promise} Egy ígéret, amely visszaadja a város adatait.
     */
    getCityByName(name) {
        return apiClient.get(`/cities/name/${name}`);
    },

    /**
     * Új várost hoz létre az API-nak küldött POST-kéréssel.
     *
     * @param {object} data - Új város adatai
     * @return {Promise} Ígéret, amely az API válaszával megoldódik.
     */
    createCity(data) {
        return apiClient.post("/cities", data);
    },

    /**
     * Frissít egy várost az API-ban.
     *
     * @param {number} id - A frissítendő város azonosítója.
     * @param {object} data - Az új város adatai.
     * @return {Promise} Ígéret, amely az API válaszával megoldódik.
     */
    updateCity(id, data) {
        return apiClient.put(`/cities/${id}`, data);
    },

    /**
     * Töröl egy várost az azonosítója alapján.
     *
     * @param {number} id - A törölni kívánt város azonosítója.
     * @return {Promise} Ígéret, amely az API válaszával megoldódik.
     */
    deleteCity(id) {
        return apiClient.delete(`/cities/${id}`);
    },
};