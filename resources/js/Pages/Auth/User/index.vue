<script setup>
import { computed, onMounted, ref, reactive } from "vue";
import { Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import AppLayout from "@/Layouts/AppLayout.vue";
import { trans } from "laravel-vue-i18n";

// Validation
import useVuelidate from "@vuelidate/core";
import { helpers, minLength, required, sameAs } from "@vuelidate/validators";

//import { usePage } from "@inertiajs/vue3";
//const page = usePage();

import UserService from "@/service/UserService";
//import functions from '../../../helpers/functions.js';

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
import Password from "primevue/password";
//import { setBaseUrl } from "@/helpers/constants";

/**
 * Szerver felöl jövő adatok
 */
const props = defineProps({});

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
 * Reaktív hivatkozás a felhasználók adatainak tárolására.
 *
 * @type {Array}
 */
const users = ref();

/**
 * Reaktív hivatkozás a felhasználó adatainak megjelenítő párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const userDialog = ref(false);

/**
 * Reaktív hivatkozás a felhasználók törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteSelectedUsersDialog = ref(false);

/**
 * Reaktív hivatkozás a jelszó módosító párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const changePwdDialog = ref(false);

/**
 * Reaktív hivatkozás a kijelölt felhasználó(ok) törléséhez használt párbeszédpanel megnyitásához.
 *
 * @type {ref<boolean>}
 */
const deleteUserDialog = ref(false);

/**
 * Reaktív hivatkozás a felhasználó adatainak tárolására.
 *
 * Ebben a változóban tároljuk a felhasználó adatait, amiket a dialogban megjelenítünk.
 *
 * @type {Object}
 * @property {number} id - A felhasználó azonosítója.
 * @property {string} name - A felhasználó neve.
 * @property {string} email - A felhasználó e-mail címe.
 * @property {string} language - A felhasználó által használt nyelv. (hu, en)
 */
const user = ref({
    id: null,
    name: "",
    email: "",
    language: "hu",
});

const pwd = ref({
    password: "",
    confirm_password: "",
});

/**
 * Reaktív hivatkozás a kijelölt felhasználók tárolására.
 *
 * @type {Array}
 */
const selectedUsers = ref();

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
        required: helpers.withMessage("validate_name", required),
    },
    email: {
        required: helpers.withMessage("validate_user_id", required),
    },
};

const pwd_rules = {
    password: {
        required: helpers.withMessage(trans("validate_required"), required),
        minLength: helpers.withMessage(
            trans("validate_min.string"),
            minLength(8),
        ),
    },
    confirm_password: {
        required: helpers.withMessage(trans("validate_required"), required),
        sameAsPassword: helpers.withMessage(
            trans(""),
            sameAs(pwd.value.password),
        ),
    },
};

/**
 * Létrehozza a validációs példányt a validációs szabályok alapján.
 *
 * @type {Object}
 */
const v$ = useVuelidate(rules, user);
const v_pwd$ = useVuelidate(pwd_rules, pwd);

// ======================================================

/**
 * Lekéri a felhasználók listáját az API-ból.
 *
 * Ez a funkció a felhasználók listáját lekéri az API-ból.
 * A felhasználók listája a users változóban lesz elmentve.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megoldódik.
 */
const fetchItems = () => {
    UserService.getUsers()
        .then((response) => {
            //console.log(response);
            // A felhasználók listája a users változóban lesz elmentve
            users.value = response.data.data;
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("getUsers API Error:", error);
        });
};

/**
 * Eseménykezelő, amely a komponens létrejöttekor hívódik meg.
 *
 * Ez a funkció a felhasználók listáját lekéri az API-ból, amikor a komponens létrejön.
 * A felhasználók listája a users változóban lesz elmentve.
 *
 * @return {void}
 */
onMounted(() => {
    fetchItems();
});

/**
 * Megerősíti a kiválasztott termékek törlését.
 *
 * Ez a funkció akkor hívódik meg, ha a felhasználó törölni szeretné a kiválasztott termékeket.
 * A deleteUsersDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    // Állítsa a deleteUserssDialog változó értékét igazra,
    // amely megnyitja a megerősítő párbeszédablakot a kiválasztott termékek törléséhez.
    deleteSelectedUsersDialog.value = true;
}

/**
 * Nyitja meg az új felhasználó dialógusablakot.
 *
 * Ez a függvény a user változó értékét alaphelyzetbe állítja, a submitted változó értékét False-ra állítja,
 * és a userDialog változó értékét igazra állítja, amely megnyitja az új felhasználó dialógusablakot.
 *
 * @return {void}
 */
function openNew() {
    user.value = { ...initialUser };
    submitted.value = false;
    userDialog.value = true;
}

/**
 * Az új felhasználó objektum alapértelmezett értékei.
 *
 * Ebben a változóban tároljuk a felhasználó adatait, amiket a dialógusablakban
 * megjelenítünk. A dialógusablakban megadott adatokkal ez az objektum lesz
 * feltöltve.
 *
 * @type {Object}
 * @property {number} id - A felhasználó azonosítója.
 * @property {string} name - A felhasználó neve.
 * @property {string} email - A felhasználó e-mail címe.
 * @property {string} language - A felhasználó által használt nyelv.
 */
const initialUser = {
    id: null,
    name: "",
    email: "",
    language: "hu",
};

/**
 * Bezárja a dialógusablakot.
 *
 * Ez a függvény a dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A v$.value.$reset() függvénnyel visszaállítja a validációs objektumot az alapértelmezett állapotába.
 */
const hideDialog = () => {
    userDialog.value = false;
    submitted.value = false;

    // Visszaállítja a validációs objektumot az alapértelmezett állapotába.
    v$.value.$reset();
};

/**
 * Szerkeszti a kiválasztott felhasználót.
 *
 * Ez a funkció a kiválasztott felhasználó adatait másolja a user változóba,
 * és megnyitja a dialógusablakot a felhasználó szerkesztéséhez.
 *
 * @param {object} data - A kiválasztott felhasználó adatai.
 * @return {void}
 */
const editUser = (data) => {
    // Másolja a kiválasztott felhasználó adatait a user változóba.
    user.value = { ...data };

    var user2 = UserService.getUser(data.id);
    console.log(user2);

    // Nyissa meg a dialógusablakot a felhasználó szerkesztéséhez.
    userDialog.value = true;
};

/**
 * Jelszó módosító funkció.
 *
 * Ez a funkció a kiválasztott felhasználó adatait másolja a user változóba,
 * és megnyitja a jelszó módosító dialógusablakot.
 *
 * @param {object} data - A kiválasztott felhasználó adatai.
 * @return {void}
 */
const pwdChange = (data) => {
    // Másolja a kiválasztott felhasználó adatait a user változóba.
    user.value = { ...data };

    // Állítsa a pwd változó id-jét a kiválasztott felhasználó id-jére.
    pwd.value.id = user.value.id;

    // Nyissa meg a jelszó módosító dialógusablakot.
    changePwdDialog.value = true;
};

/**
 * Bezárja a jelszó módosító dialógusablakot.
 *
 * Ez a függvény a jelszó módosító dialógusablakot bezárja, és a submitted változó értékét False-ra állítja.
 * A user és a pwd változókat is alaphelyzetbe állítja.
 */
const hidePwrd = () => {
    // Alaphelyzetbe állítja a user változót.
    user.value = {};

    // Alaphelyzetbe állítja a pwd változót.
    pwd.value = {};

    // A submitted változó értékét False-ra állítja.
    submitted.value = false;

    // Bezárja a jelszó módosító dialógusablakot.
    changePwdDialog.value = false;

    v_pwd$.value.$reset();
};

const updatePwd = async () => {
    const result = await v_pwd$.value.$validate();

    if (result) {
        submitted.value = true;

        UserService.updatePassword(user.value.id, pwd.value)
            .then((response) => {
                console.log("response", response);

                hidePwrd();
            })
            .catch((error) => {
                console.log("error", error);
            });
    } else {
        console.log(v_pwd$.value.$errors);
    }
};

/**
 * Megerősítés a felhasználó törléséhez.
 *
 * Ez a funkció a user változóba másolja a kiválasztott felhasználó adatait,
 * és megnyitja a dialógusablakot a felhasználó törléséhez.
 *
 * @param {object} data - A kiválasztott felhasználó adatai.
 * @return {void}
 */
const confirmDeleteUser = (data) => {
    // Másolja a kiválasztott felhasználó adatait a user változóba.
    user.value = { ...data };

    // Nyissa meg a dialógusablakot a felhasználó törléséhez.
    deleteUserDialog.value = true;
};

/**
 * Mentse el a felhasználó adatait az API-ban.
 *
 * A metódus ellenörzi a felhasználó adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor elmenti a felhasználó adatait az API-ban.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megold dik.
 */
const saveUser = async () => {
    // Ellen rizi a felhasználó adatait a validációs szabályok alapján.
    const result = await v$.value.$validate();

    // Ha a validáció sikerült, akkor mentse el a felhasználó adatait az API-ban.
    if (result) {
        submitted.value = true;

        // Ha a felhasználónak van azonosítója, akkor frissítse a felhasználó adatait.
        if (user.value.id) {
            updateUser();
        } else {
            // Ellenkez  esetben hozzon létre egy új felhasználót az API-ban.
            createUser();
        }
    } else {
        // Ha a validáció nem sikerült, akkor jelenítsen meg egy figyelmeztetést.
        alert("FAIL");
    }
};

/**
 * Hozzon létre új felhasználót az API-nak küldött POST-kéréssel.
 *
 * A metódus ellenörzi a felhasználó adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor létrehoz egy új felhasználót az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const createUser = () => {
    UserService.createUser(user.value)
        .then((response) => {
            //console.log('response', response);
            users.values.push(response.data);

            hideDialog();

            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "User Created",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("createUser API Error:", error);
        });
};

/**
 * Frissít egy felhasználót az API-nak küldött PUT-kéréssel.
 *
 * A metódus ellenörzi a felhasználó adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor frissíti a felhasználót az API-ban.
 * A választ megjeleníti a konzolon.
 *
 * @param {number} id - A frissítendő felhasználó azonosítója.
 * @param {object} data - A felhasználó új adatai.
 * @return {Promise} Ígéret, amely a válaszban szereplő adatokkal megoldódik.
 */
const updateUser = () => {
    UserService.updateUser(user.value.id, user.value)
        .then(() => {
            // Megkeresi a felhasználó indexét a felhasználók tömbjében az azonosítója alapján
            const index = findIndexById(user.value.id);
            // A felhasználó adatait frissíti a felhasználók tömbjében
            users.value.splice(index, 1, user.value);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Siker-értesítést jelenít meg
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "User Updated",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("updateUser API Error:", error);
        });
};

/**
 * Megkeresi egy felhasználó indexét a felhasználók tömbjében az azonosítója alapján.
 *
 * @param {number} id - A keresendő felhasználó azonosítója.
 * @returns {number} A felhasználó indexe a felhasználók tömbjében, vagy -1, ha nem található.
 */
const findIndexById = (id) => {
    return users.value.findIndex((user) => user.id === id);
};

/**
 * Törli a kiválasztott felhasználót az API-ból.
 *
 * A metódus ellenörzi a felhasználó adatait a validációs szabályok alapján,
 * és ha a validáció sikerült, akkor törli a felhasználót az API-ból.
 * A választ megjeleníti a konzolon.
 *
 * @return {Promise} Ígéret, amely a válaszban szerepl  adatokkal megold dik.
 */
const deleteUser = () => {
    // Ellen rizi a felhasználó adatait a validációs szabályok alapján.
    if (v$.value.$invalid) {
        alert("FAIL");
        return;
    }

    // Törli a felhasználót az API-ból.
    UserService.deleteUser(user.value.id)
        .then((response) => {
            // Megkeresi a felhasználó indexét a felhasználók tömbjében az azonosítója alapján
            const index = findIndexById(user.value.id);
            // A felhasználó adatait törli a felhasználók tömbjéb l
            users.value.splice(index, 1);

            // Bezárja a dialógus ablakot
            hideDialog();

            // Siker-értesítést jelenít meg
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "User Deleted",
                life: 3000,
            });
        })
        .catch((error) => {
            // Jelenítse meg a hibaüzenetet a konzolon
            console.error("deleteUser API Error:", error);
        });
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const deleteSelectedUsers = () => {
    console.log(selectedUsers.value);
};

const getActiveLabel = (user) =>
    ["danger", "success", "warning"][user.active || 2];

const getActiveValue = (user) =>
    ["inactive", "active", "pending"][user.active] || "pending";

/**
 * Visszaadja a dialógusablak címét attól függően,
 * hogy új felhasználót hozunk létre, vagy egy meglévőt szerkesztünk.
 *
 * @returns {string} A dialógusablak címe.
 */
const getModalTitle = () => {
    return user.value.id ? trans("users_edit_title") : trans("users_new_title");
};

/**
 * Visszaadja a dialógusablak részleteit attól függően,
 * hogy új felhasználót hozunk létre, vagy egy meglévőt szerkesztünk.
 *
 * @returns {string} A dialógusablak részletei.
 */
const getModalDetails = () => {
    return user.value.id
        ? trans("users_edit_details")
        : trans("users_new_details");
};
</script>

<template>
    <AppLayout>
        <Head :title="$t('users')" />

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
                        :disabled="!selectedUsers || !selectedUsers.length"
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
                v-model:selection="selectedUsers"
                :value="users"
                dataKey="id"
                :paginator="true"
                :rows="10"
                :filters="filters"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                :rowsPerPageOptions="[5, 10, 25]"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords} users"
            >
                <template #header>
                    <div
                        class="flex flex-wrap gap-2 items-center justify-between"
                    >
                        <!-- FELIRAT -->
                        <div class="font-semibold text-xl mb-1">
                            {{ $t("users_title") }}
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
                            @click="editUser(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-lock"
                            outlined
                            rounded
                            class="mr-2"
                            severity="help"
                            @click="pwdChange(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            @click="confirmDeleteUser(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Felhasználó szerkesztése -->
        <Dialog
            v-model:visible="userDialog"
            :style="{ width: '550px' }"
            :header="getModalTitle()"
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
                            v-model="user.name"
                            autofocus
                            fluid
                        />
                        <small class="text-red-500" v-if="v$.name.$error">
                            {{ $t(v$.name.$errors[0].$message) }}
                        </small>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col grow basis-0 gap-2">
                        <label for="email" class="block font-bold mb-3">{{
                            $t("email")
                        }}</label>
                        <InputText
                            id="email"
                            v-model="user.email"
                            autofocus
                            fluid
                        />
                        <small class="text-red-500" v-if="v$.name.$error">
                            {{ $t(v$.email.$errors[0].$message) }}
                        </small>
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
                    @click="saveUser"
                />
            </template>
        </Dialog>

        <!-- Felhasználó törlése -->
        <!-- Egy megerősítő párbeszédpanel, amely megjelenik, ha a felhasználó törölni szeretne egy felhasználót. -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést. -->
        <Dialog
            v-model:visible="deleteUserDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <!-- A párbeszédpanel tartalma -->
            <div class="flex items-center gap-4">
                <!-- A figyelmeztető ikon -->
                <i class="pi pi-exclamation-triangle !text-3xl" />
                <!-- A szöveg, amely megjelenik a párbeszédpanelen -->
                <span v-if="user"
                    >{{ $t("confirm_delete_2") }} <b>{{ user.name }}</b
                    >?</span
                >
            </div>
            <!-- A párbeszédpanel lábléc, amely tartalmazza a gombokat -->
            <template #footer>
                <!-- A "Nem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteUserDialog = false"
                    text
                />
                <!-- A "Igen" gomb, amely törli a felhasználót -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteUser"
                />
            </template>
        </Dialog>

        <!-- Erősítse meg a kiválasztott felhasználók törlését párbeszédpanelen -->
        <!-- Ez a párbeszédpanel akkor jelenik meg, ha a felhasználó több felhasználót szeretne törölni -->
        <!-- A párbeszédpanel felkéri a felhasználót, hogy erősítse meg a törlést -->
        <Dialog
            v-model:visible="deleteSelectedUsersDialog"
            :style="{ width: '450px' }"
            :header="$t('confirm')"
            :modal="true"
        >
            <template #header>
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="user">{{ $t("confirm_delete") }}</span>
                </div>
            </template>

            <template #footer>
                <!-- "Mégsem" gomb -->
                <Button
                    :label="$t('no')"
                    icon="pi pi-times"
                    @click="deleteSelectedUsersDialog = false"
                    text
                />
                <!-- Megerősítés gomb -->
                <Button
                    :label="$t('yes')"
                    icon="pi pi-check"
                    @click="deleteSelectedUsers"
                />
            </template>
        </Dialog>

        <!-- Jelszó módosítása párbeszédpanel -->
        <!-- Ez a párbeszédablak akkor jelenik meg, ha a felhasználó meg akarja változtatni a jelszavát -->
        <!-- A párbeszédpanelen egy figyelmeztető ikon és egy „Jelszó módosítása” szöveg látható. -->
        <!-- A párbeszédpanelen két gomb található: "Mégse" és "Módosítás" -->
        <Dialog
            v-model:visible="changePwdDialog"
            :style="{ width: '450px' }"
            :header="$t('change_password')"
            :modal="true"
        >
            <div class="flex flex-col gap-6">
                <!-- JELSZÓ -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="password" class="block font-bold mb-3">
                        {{ $t("password") }}
                    </label>
                    <Password
                        id="password"
                        v-model="pwd.password"
                        autofocus
                        fluid
                        toggleMask
                    />
                    <small class="text-red-500" v-if="v_pwd$.password.$error">
                        {{ $t(v_pwd$.password.$errors[0].$message) }}
                    </small>
                </div>

                <!-- JELSZÓ MEGERŐSÍTÉS -->
                <div class="flex flex-col grow basis-0 gap-2">
                    <label for="confirm_password" class="block font-bold mb-3">
                        {{ $t("confirm_password") }}
                    </label>
                    <Password
                        id="confirm_password"
                        v-model="pwd.confirm_password"
                        autofocus
                        fluid
                        toggleMask
                    />
                    <small
                        class="text-red-500"
                        v-if="v_pwd$.confirm_password.$error"
                    >
                        {{ $t(v_pwd$.confirm_password.$errors[0].$message) }}
                    </small>
                </div>
            </div>

            <!-- A párbeszédablak lábléce -->
            <template #footer>
                <!-- A "Mégse" gomb -->
                <Button
                    :label="$t('cancel')"
                    icon="pi pi-times"
                    text
                    @click="hidePwrd"
                />
                <!-- A "Változtatás" gomb -->
                <Button
                    :label="$t('change')"
                    icon="pi pi-database"
                    @click="updatePwd"
                />
            </template>
        </Dialog>
    </AppLayout>
</template>
