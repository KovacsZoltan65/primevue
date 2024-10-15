<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, required, minLength, maxLength } from "@vuelidate/validators";
import validationRules from "../../Validation/ValidationRules.json";

import StateService from "@/service/SubdomainStateService";

import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import IconField from "primevue/iconfield";
import InputText from "primevue/inputtext";
import InputIcon from "primevue/inputicon";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Tag from "primevue/tag";
import SubdomainService from "@/service/SubdomainService";

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({
    /**
     * Országok adatai.
     */
    //subdomains: {
    //    type: Object,
    //    default: () => {},
    //},
    /**
     * Régiók adatai.
     */
    //regions: {
    //    type: Object,
    //    default: () => {},
    //},
});

/**
 * Az állapotmező logikai értékeit adja vissza.
 *
 * @returns {Array<Object>} objektumok tömbje címke és érték tulajdonságokkal.
 */
const getBools = () => {
    return [
        {
            /**
             * Az inaktív állapot címkéje.
             */
            label: trans("inactive"),
            /**
             * Az inaktív állapot értéke.
             */
            value: 0,
        },
        {
            /**
             * Az aktív állapot címkéje.
             */
            label: trans("active"),
            /**
             * Az aktív állapot értéke.
             */
            value: 1,
        },
    ];
};

/**
 * Használja a PrimeVue toast összetevőjét.
 *
 * @type {Object}
 */
const toast = useToast();

/**
 * Reaktív hivatkozás a datatable komponensre.
 *
 * @type {Object}
 */
const dt = ref();

/**
 * Reaktív hivatkozás a városok adatainak tárolására.
 *
 * @type {Array}
 */
const states = ref();

/**
 * Reaktív hivatkozás a város adatainak megjelenítő párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const stateDialog = ref(false);

/**
 * Reaktív hivatkozás a városok törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteSelectedStatesDialog = ref(false);

/**
 * Reaktív hivatkozás a kijelölt város(ok) törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteStateDialog = ref(false);

/**
 * Reaktív hivatkozás a város adatainak tárolására.
 *
 * @type {Object}
 * @property {number} id - A város azonosítója.
 * @property {string} name - A város neve.
 * @property {number} active - A város státusza.
 */
const state = ref({
    id: null,
    name: "",
    active: 1,
});

/**
 * Reaktív hivatkozás a kijelölt városok tárolására.
 *
 * @type {Array}
 */
const selectedStates = ref();

/**
 * Reaktív hivatkozás a globális keresés szűrőinek tárolására az adattáblában.
 *
 * @type {Object}
 */
const filters = ref({
    // A globális szűrőobjektum.
    // Van egy érték tulajdonsága a keresési lekérdezés tárolására
    // és egy matchMode tulajdonsága a keresés típusának megadásához.
    global: {
        /**
         * A globális szűrő értéke.
         *
         * @type {string | null}
         */
        value: null,

        /**
         * A globális szűrő illesztési módja.
         *
         * @type {FilterMatchMode}
         */
        matchMode: FilterMatchMode.CONTAINS,
    },
});

/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 *
 * @type {ref<boolean>}
 */
const submitted = ref(false);

const rules = {
    name: {
        required: helpers.withMessage( trans("validate_name"), required),
        minLength: helpers.withMessage( ({ $params }) => trans('validate_min.string', { min: $params.min }), minLength(validationRules.minStringLength)),
        maxLength: helpers.withMessage( ({ $params }) => trans('validate_max.string', { max: $params.max }), maxLength(validationRules.maxStringLength)),
    },
};

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, state);

// ======================================================

/**
 * Lekéri a subdomain állapotok listáját az API-ból.
 *
 * Ez a funkció a subdomain állapotok listáját lekéri az API-ból.
 * A subdomain állapotok listája a states változóban lesz elmentve.
 *
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const fetchItems = () => {
    StateService.getSubdomainStates()
        .then((response) => {
            // A városok listája a subdomains változóban lesz elmentve
            states.value = response.data.data;
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getSubdomainStates API Error:", error);
        });
};

/**
 * Eseménykezelő, amely a komponens létrejöttekor hívódik meg.
 *
 * Ez a funkció a városok listáját lekéri az API-ból, amikor a komponens létrejön.
 * A városok listája a subdomains változóban lesz elmentve.
 *
 * @return {void}
 */
onMounted(() => {
    fetchItems();
});

/**
 * Megerősíti a kiválasztott állapotok törlését.
 *
 * Ez a funkció akkor hívódik meg, ha a felhasználó törölni szeretné a kiválasztott állapotokat.
 * A deleteStatesDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott állapotok törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    deleteSelectedStatesDialog.value = true;
}

/**
 * Nyitja meg az új állapot dialógusablakot.
 *
 * Ez a függvény a states változó értékét alaphelyzetbe állítja, 
 * a submitted változó értékét False-ra állítja, és a stateDialog 
 * változó értékét igazra állítja, amely megnyitja az új állapot dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    state.value = { ...initialState };
    submitted.value = false;
    stateDialog.value = true;
}

/**
 * Az új város objektum alapértelmezett értékei.
 *
 * A subdomainDialog változó értékét igazra állítva, ez az objektum lesz a dialógusablakban
 * megjelenő új város formban.
 *
 * @type {Object}
 * @property {string} name - Az állapot neve.
 * @property {number} active - Az állapot aktív-e? (1 igen, 0 nem).
 * @property {number} id - Az állapot azonosítója.
 */
const initialState = {
    id: null,
    name: "",
    active: 1,
};

/**
 * Bezárja a dialógusablakot.
 *
 * Ez a függvény a dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A v$.value.$reset() függvénnyel visszaállítja a validációs objektumot az alapértelmezett állapotába.
 */
const hideDialog = () => {
    stateDialog.value = false;
    submitted.value = false;

    // Visszaállítja a validációs objektumot az alapértelmezett állapotába.
    v$.value.$reset();
};

/**
 * Szerkeszti a kiválasztott állapotot.
 *
 * Ez a funkció a kiválasztott állapot adatait másolja a state változóba,
 * és megnyitja a dialógusablakot az állapot szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott állapot adatai.
 * @return {void}
 */
const editState = (data) => {
    // Másolja a kiválasztott állapot adatait a subdomain változóba.
    state.value = { ...data };

    // Nyissa meg a dialógusablakot a város szerkesztéséhez.
    stateDialog.value = true;
};

/**
 * Megerősítés az állapot törléséhez.
 *
 * Ez a funkció a state változóba másolja a kiválasztott állapot adatait,
 * és megnyitja a dialógusablakot az állapot törléséhez.
 *
 * @param {object} data - A kiválasztott állapot adatai.
 * @return {void}
 */
const confirmDeleteState = (data) => {
    // Másolja a kiválasztott állapot adatait a state változóba.
    state.value = { ...data };

    // Nyissa meg a dialógusablakot az állapot törléséhez.
    deleteStateDialog.value = true;
};

const saveState = async () => {
    const result = await v$.value.$validate();
    if (result) {
        submitted.value = true;

        if (state.value.id) {
            updateState();
        } else {
            createState();
        }
    } else {
        alert("FAIL");
    }
};

/**
 * Hozzon létre új állapotot az API-nak küldött POST-kéréssel.
 *
 * A metódus ellenörzi az állapot adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor létrehoz egy új állapotot az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const createState = () => {
    StateService.createSubdomainState(state.value)
        .then((response) => {
            //console.log('response', response);
            states.values.push(response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "SubdomainState Created",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("createSubdomainState API Error:", error);
        });
};

/**
 * Frissít egy állapotot az API-nak küldött PUT-kéréssel.
 *
 * A metódus ellenörzi az állapot adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor frissíti az állapotot az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @param {number} id - A frissítendő állapot azonosítója.
 * @param {object} data - Az állapot új adatai.
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const updateState = () => {
    StateService.updateSubdomain(state.value.id, state.value)
        .then(() => {
            // Megkeresi az állapot indexét a városok tömbjében az azonosítója alapján
            const index = findIndexById(state.value.id);
            // A város adatait frissíti a városok tömbjében
            states.value.splice(index, 1, state.value);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Siker-értesítést jelenít meg
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "SubdomainState Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("updateSubdomainState API Error:", error);
        });
};

/**
 * Megkeresi egy állapot indexét az állapotok tömbjében az azonosítója alapján.
 *
 * @param {number} id - A keresendő állapot azonosítója.
 * @returns {number} Az állapot indexe az állapotok tömbjében, vagy -1, ha nem található.
 */
const findIndexById = (id) => {
    return states.value.findIndex((state) => state.id === id);
};

/**
 * Törli az állapotot az API-ból.
 *
 * A metódus ellenörzi az állapotot a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor törli az állapotot az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @param {number} id - A törölni kívánt állapot azonosítója.
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const deleteState = () => {
    StateService.deleteSubdomainState(state.value.id)
        .then((response) => {
            // Törli az állapotot a városok tömbjéb l
            const index = findIndexById(state.value.id);
            states.value.splice(index, 1);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Siker-értesítést jelenít meg
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "SubdomainState Deleted",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("deleteSubdomainState API Error:", error);
        });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const deleteSelectedStates = () => {
    
    SubdomainService.deleteSubdomainStates(selectedStates.value);
    
    states.value = states.value.filter(
        (val) => !selectedStates.value.includes(val),
    );
    deleteStateDialog.value = false;
    selectedStates.value = null;

    toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Subdomain Deleted",
        life: 3000,
    });
};

const getActiveLabel = (subdomain) =>
    ["danger", "success", "warning"][subdomain.active || 2];

const getActiveValue = (subdomain) =>
    ["inactive", "active", "pending"][subdomain.active] || "pending";
</script>

<template>
    <AppLayout>
        <Head :title="$t('subdomains')" />

        <div class="card">
            <Toolbar class="md-6">
                <template #start>
                    <Button
                        :label="$t('new')"
                        icon="pi pi-plus"
                        severity="secondary"
                        class="mr-2"
                        @click="openNew"
                    />
                    <Button
                        :label="$t('delete')"
                        icon="pi pi-trash"
                        severity="secondary"
                        @click="confirmDeleteSelected"
                        :disabled="
                            !selectedStates || !selectedStates.length
                        "
                    />
                </template>

                <template #end>
                    <Button
                        :label="$t('export')"
                        icon="pi pi-upload"
                        severity="secondary"
                        @click="exportCSV($event)"
                    />
                </template>
            </Toolbar>

            <DataTable
                ref="dt"
                v-model:selection="selectedStates"
                :value="states"
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} subdomains"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("subdomains_title") }}
                        </div>

                        <!-- KERESÉS -->
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText
                                v-model="filters['global'].value"
                                :placeholder="$t('search')"
                            />
                        </IconField>
                    </div>
                </template>

                <!-- Checkbox -->
                <Column
                    selectionMode="multiple"
                    style="min-width: 3rem"
                    :exportable="false"
                />

                <!-- Nev -->
                <Column
                    field="name"
                    :header="$t('name')"
                    sortable
                    style="min-width: 16rem"
                />

                <!-- Active -->
                <Column
                    field="active"
                    :header="$t('active')"
                    sortable
                    style="min-width: 6rem"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="$t(getActiveValue(slotProps.data))"
                            :severity="getActiveLabel(slotProps.data)"
                        />
                    </template>
                </Column>

                <!-- Actions -->
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2"
                            @click="editState(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteState(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Állapot szerkesztése -->
        <Dialog
            v-model:visible="stateDialog"
            :style="{ width: '550px' }"
            :header="$t('subdomain_states_details')"
            :modal="true"
        >
            <div class="flex flex-col gap-6">
                <div class="flex flex-wrap gap-4">
                    <!-- Name -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="name" class="block font-bold mb-3">{{
                            $t("name")
                        }}</label>
                        <InputText
                            id="name"
                            v-model="state.name"
                            autofocus
                            fluid
                        />
                        <small class="text-red-500" v-if="v$.name.$error">
                            {{ $t(v$.name.$errors[0].$message) }}
                        </small>
                    </div>

                    <div class="flex flex-col grow basis-0 gap-2">
                        <!-- Active -->
                        <label
                            for="active"
                            class="block font-bold mb-3"
                        >
                            {{ $t("active") }}
                        </label>
                        <Select
                            id="active"
                            name="active"
                            v-model="state.active"
                            :options="getBools()"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="SubdomainStates"
                        />
                    </div>
                </div>
            </div>

            <template #footer>
                <Button
                    :label="$t('cancel')"
                    icon="pi pi-times"
                    text
                    @click="hideDialog"
                />
                <Button
                    :label="$t('save')"
                    icon="pi pi-check"
                    @click="saveState"
                />
            </template>
        </Dialog>

        <!-- Állapot törlése -->
        <!-- Egy megerősítő párbeszédpanel, amely megjelenik, ha a felhasználó törölni szeretne egy állapotot. -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést. -->
        <Dialog
            v-model:visible="deleteStateDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <!-- A párbeszédpanel tartalma -->
            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="state"
                    >{{ $t("confirm_delete_2") }} <b>{{ state.name }}</b
                    >?</span
                >
            </div>

            <!-- A párbeszédpanel lábléc, amely tartalmazza a gombokat -->
            <template #footer>
                <!-- A "Nem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteStateDialog = false"
                    text
                />
                <!-- A "Igen" gomb, amely törli az állapotot -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteState"
                />
            </template>
        </Dialog>

        <!-- Erősítse meg a kiválasztott állapotok törlését párbeszédpanelen -->
        <!-- Ez a párbeszédpanel akkor jelenik meg, ha a felhasználó több állapotot szeretne törölni -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést -->
        <Dialog
            v-model:visible="deleteSelectedStatesDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <div class="flex items-center gap-4">
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <span v-if="state">{{ $t("confirm_delete") }}</span>
            </div>
            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedStatesDialog = false"
                    text
                />
                <!-- Megerősítés gomb -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedStates"
                />
            </template>
        </Dialog>
    </AppLayout>
</template>
