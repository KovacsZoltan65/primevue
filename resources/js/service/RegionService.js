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
     * Lekéri a régiókat az API-ból.
     *
     * @return {Promise} Ígéret, amely a régiók listájával megoldódik.
     */
    getRegions: () => {
        return apiClient.get("/api/regions");
    },

    /**
     * Lekér egy régiót az azonosítója alapján az API-ból.
     *
     * @param {number} id - A lekérni kívánt régió azonosítója.
     * @return {Promise} Ígéret, ami a régió adataival tér vissza.
     */
    getRegion: (id) => {
        return apiClient.get(`/regions/${id}`);
    },

    /**
     * Új régiót hoz létre az API-nak küldött POST-kéréssel.
     *
     * @param {object} data - Az új régió adatai.
     * @return {Promise} Ígéret, amely az API válaszával megoldódik.
     */
    createRegion: (data) => {
        return apiClient.post("/regions", data);
    },

    /**
     * Frissít egy régiót az azonosítója alapján.
     *
     * @param {number} id - A frissítendő régió azonosítója.
     * @param {object} data - Az új régió adatai.
     * @return {Promise} Ígéret, amely az API válaszával megoldódik.
     */
    updateRegion: (id, data) => {
        return apiClient.put(`/regions/${id}`, data);
    },

    /**
     * Töröl egy régiót az azonosítója alapján.
     *
     * @param {number} id - A törölni kívánt régió azonosítója.
     * @return {Promise} Ígéret, amely az API válaszával megoldódik.
     */
    deleteRegion: (id) => {
        return apiClient.delete(`/regions/${id}`);
    },
};
