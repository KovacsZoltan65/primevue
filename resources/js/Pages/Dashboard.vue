<script setup>
import { onMounted, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

import { ProductService } from '../service/ProductService';
import { useToast } from 'primevue/usetoast';
import { FilterMatchMode } from '@primevue/core/api';
import Button from 'primevue/button';
import Toolbar from 'primevue/toolbar';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Rating from 'primevue/rating';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import Select from 'primevue/select';
import InputNumber from 'primevue/inputnumber';
import RadioButton from 'primevue/radiobutton';

/**
 * Reaktív hivatkozás a toast komponensre.
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
 * Reaktív hivatkozás a termékek adatainak tárolására.
 * 
 * @type {Array}
 */
const products = ref();

/**
 * Reaktív hivatkozás a termék adatainak megjelenítő párbeszédpanel megnyitásához.
 * 
 * @type {Boolean}
 */
const productDialog = ref(false);

/**
 * Reaktív hivatkozás a termék törléséhez használt párbeszédpanel megnyitásához.
 * 
 * @type {Boolean}
 */
const deleteProductDialog = ref(false);

/**
 * Reaktív hivatkozás a kijelölt termékek törléséhez használt párbeszédpanel megnyitásához.
 * 
 * @type {Boolean}
 */
const deleteProductsDialog = ref(false);

/**
 * Reaktív hivatkozás a termék adatainak tárolására.
 * 
 * @type {Object}
 */
const product = ref({});

/**
 * Reaktív hivatkozás a kijelölt termékek tárolására.
 * 
 * @type {Array}
 */
const selectedProducts = ref();

/**
 * Reaktív hivatkozás a globális keresés szűrőinek tárolására az adattáblában.
 * 
 * @type {Object}
 */
const filters = ref({
    // A globális szűrőobjektum.
    // Van egy érték tulajdonsága a keresési lekérdezés tárolására és egy matchMode tulajdonsága a keresés típusának megadásához.
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
        matchMode: FilterMatchMode.CONTAINS
    }
});

/**
 * Reaktív hivatkozás a beküldött (submit) állapotára.
 * 
 * @type {Ref<boolean>}
 */
const submitted = ref(false);

/**
 * Reaktív hivatkozás a termék állapotai (status) lehetőségére a kijelöléshez.
 * Alapértelmezett érték: [
 *     { label: 'INSTOCK', value: 'instock' },
 *     { label: 'LOWSTOCK', value: 'lowstock' },
 *     { label: 'OUTOFSTOCK', value: 'outofstock' }
 * ]
 * 
 * @type {ref<Array>}
 */
const statuses = ref([
    /**
     * Az INSTOCK állapot megfelel a rendelkezésre álló készletről.
     * 
     * @type {Object}
     */
    { label: 'INSTOCK', value: 'instock' },

    /**
     * A LOWSTOCK állapot megfelel a kis készléről.
     * 
     * @type {Object}
     */
    { label: 'LOWSTOCK', value: 'lowstock' },

    /**
     * Az OUTOFSTOCK állapot megfelel a készlet nélkül.
     * 
     * @type {Object}
     */
    { label: 'OUTOFSTOCK', value: 'outofstock' }
]);

/**
 * A komponens betöltésekor lekérdezjük a termékeket a ProductService-ből.
 * A lekérdezett termékeket a products hivatkozásban tároljuk.
 *
 * @return {void}
 */
 onMounted(() => {
    
    ProductService.getProducts().then((data) => {
        products.value = data;
    });

    /*
    // A ProductService getProducts() metódusát hívjuk meg,
    // amely visszaadja a termékeket.
    ProductService.getProd()
        .then((data) => {
            // A lekérdezett termékeket a products hivatkozásban tároljuk.
            products.value = data;
        });
    */
});

/**
 * Egy számot valutakarakterláncként formáz.
 *
 * @param {number | undefined} value - A formázandó szám.
 * @return {string | undefined} A formázott pénznem-karakterlánc, vagy hamis az érték definiálatlan.
 */
function formatCurrency(value) {
    // Ha az érték false (undefined, null, 0 stb.), adja vissza az undefined értéket.
    if (!value) return;

    // Formázza a számot valutakarakterláncként az amerikai angol nyelvterület és az USD pénznem használatával.
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
}

/**
 * Ez a funkció megnyitja az új termék párbeszédpanelt.
 * A termék objektumot üres objektummá inicializálja,
 * a beküldött jelzőt false értékre, a productDialog jelzőt pedig igazra állítja.
 *
 * @return {void}
 */
function openNew() {
    // Inicializálja a termékobjektumot egy üres objektummá.
    product.value = {};

    // Állítsa be a beküldött jelzőt false értékre.
    submitted.value = false;

    // A párbeszédpanel megnyitásához állítsa a productDialog jelzőt true értékre.
    productDialog.value = true;
}

/**
 * Ez a funkció a termék párbeszédpanel elrejtésére szolgál.
 * A productDialog hivatkozás értékét false értékre állítja,
 * és a beküldött hivatkozás false.
 *
 * @return {void}
 */
function hideDialog() {
    // A termék párbeszédpanel elrejtése a productDialog értékének false értékre állításával.
    productDialog.value = false;

    // Állítsa be a beküldött értéket false értékre.
    submitted.value = false;
}

/**
 * Ez a funkció elmenti a terméket.
 * A beküldött értéket igazra állítja, majd ellenőrzi, hogy a terméknév nem üres-e, vagy csak szóközt tartalmaz-e.
 * Ha a termék rendelkezik azonosítóval, akkor frissíti a készlet állapotát, és lecseréli a terméktömbben lévő terméket a frissített termékre.
 * Ezután megjelenik egy sikeres pirítós üzenet, amely jelzi, hogy a termék frissítve lett.
 * Ha a terméknek nincs azonosítója, akkor új azonosítót, kódot generál, és a képet a „product-placeholder.svg” értékre állítja.
 * A készlet állapotát is beállítja, ha nincs megadva.
 * Hozzáadja az új terméket a termékek tömbhöz, és egy sikeres pirítós üzenetet jelenít meg, jelezve, hogy a termék létrejött.
 * Végül elrejti a termék párbeszédpanelt és törli a termék objektumot.
 *
 * @return {void}
 */
function saveProduct() {
    // Állítsa a beküldött értéket igazra.
    submitted.value = true;

    // Ellenőrizze, hogy a termék neve nem üres-e, vagy csak szóközt tartalmaz-e.
    if (product?.value.name?.trim()) {
        // Ellenőrizze, hogy a termék rendelkezik-e azonosítóval.
        if (product.value.id) {
            // Frissítse a készlet állapotát.
            product.value.inventoryStatus = product.value.inventoryStatus.value ? product.value.inventoryStatus.value : product.value.inventoryStatus;

            // Cserélje ki a terméket a terméktömbben a frissített termékre.
            products.value[findIndexById(product.value.id)] = product.value;

            // Jelenítse meg a sikeres toast üzenetet, amely jelzi, hogy a termék frissítve lett.
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Product Updated', life: 3000 });
        } else {
            // Hozzon létre egy új azonosítót, kódot, és állítsa be a képet a „product-placeholder.svg” értékre.
            product.value.id = createId();
            product.value.code = createId();
            product.value.image = 'product-placeholder.svg';

            // Állítsa be a készletállapotot, ha nincs megadva.
            product.value.inventoryStatus = product.value.inventoryStatus ? product.value.inventoryStatus.value : 'INSTOCK';

            // Adja hozzá az új terméket a terméktömbhöz.
            products.value.push(product.value);

            // Jelenítsen meg egy sikeres toast üzenetet, amely jelzi, hogy a termék elkészült.
            toast.add({ severity: 'success', summary: 'Successful', detail: 'Product Created', life: 3000 });
        }

        // A termék párbeszédpanel elrejtése és a termékobjektum törlése.
        productDialog.value = false;
        product.value = {};
    }
}

/**
 * Szerkesszen egy terméket.
 *
 * @param {Object} prod - A szerkesztendő termék.
 * @return {void}
 */
function editProduct(prod) {
    // Hozzon létre egy másolatot a termékobjektumról, és rendelje hozzá a termékértékhez.
    // A spread operátor egy új objektum létrehozására szolgál, amely ugyanazokkal a tulajdonságokkal rendelkezik, mint a prod.
    product.value = { ...prod };
    
    // Állítsa a productDialog értékét igazra, ami megnyitja a termék párbeszédpanelt.
    productDialog.value = true;
}

/**
 * Módosítja a deleteProductDialog értékét és a product értékét, hogy megerősítse a termék törlését.
 *
 * @param {Object} prod - A törlendő termék.
 * @return {void}
 */
function confirmDeleteProduct(prod) {
    // Módosítsa a deleteProductDialog értékét, hogy megnyissa a törlés párbeszédpanelt.
    deleteProductDialog.value = true;

    // Módosítsa a product értékét, hogy tartalmazza a törlendő termék adatait.
    product.value = prod;
}

/**
 * Ez a funkció felelős egy termék törléséért.
 * Kiszűri a terméktömbből a megfelelő azonosítóval rendelkező terméket,
 * hamisra frissíti a deleteProductDialog értéket,
 * a termék értékét üres objektumra állítja,
 * és sikeres pirítós üzenetet jelenít meg.
 *
 * @return {void}
 */
function deleteProduct() {
    // Szűrje ki a terméktömbből a megfelelő azonosítójú terméket.
    products.value = products.value.filter((val) => val.id !== product.value.id);

    // Frissítse a deleteProductDialog értéket false értékre.
    deleteProductDialog.value = false;

    // Állítsa be a termék értékét egy üres objektumra.
    product.value = {};

    // Sikeres toast üzenet megjelenítése.
    toast.add({
        severity: 'success',
        summary: 'Successful',
        detail: 'Product Deleted',
        life: 3000,
    });
}

/**
 * Megkeresi egy cikk indexét a termékek tömbben az azonosítója alapján.
 *
 * @param {number} id - A keresendő elem azonosítója.
 * @return {number} A cikk indexe a termékek tömbjében, vagy -1, ha nem található.
 */
function findIndexById(id) {
    // Használja a `findIndex` metódust, hogy gyorsan megtalálja a cikk indexét.
    return products.value.findIndex((product) => product.id === id);

    // Inicializálja az indexet -1-re, jelezve, hogy az elem nem található.
    //let index = -1;

    // Iteráljon a termékek tömb minden elemén.
    //for (let i = 0; i < products.value.length; i++) {
        // Ellenőrizze, hogy az aktuális tétel azonosítója megegyezik-e a célazonosítóval.
    //    if (products.value[i].id === id) {
            // Ha talál egyezést, frissítse az indexet, és lépjen ki a ciklusból.
    //        index = i;
    //        break;
    //    }
    //}

    // Adja vissza az elem indexét, vagy -1-et, ha nem található.
    //return index;
}

/**ucts()
 * 5 hosszúságú véletlenszerű azonosító karakterláncot generál.
 *
 * @return {string} A véletlenszerűen generált azonosító karakterlánc.
 */
function createId() {
    // Az azonosító tárolásához inicializáljon egy üres karakterláncot.
    let id = '';

    // Határozza meg az azonosító generálásához használható karaktereket.
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    // Az azonosító létrehozása véletlenszerű karakterek kiválasztásával a meghatározott készletből.
    for (let i = 0; i < 5; i++) {
        // Szerezzen be egy véletlenszerű indexet a karakterek hosszának tartományán belül.
        const randomIndex = Math.floor(Math.random() * chars.length);

        // Adja hozzá a véletlenszerű indexben lévő karaktert az azonosító karakterlánchoz.
        id += chars.charAt(randomIndex);
    }

    // Adja vissza a generált azonosító karakterláncot.
    return id;
}

/**
 * Exportálja az adattábla adatait egy CSV-fájlba.
 *
 * This function calls the exportCSV method of the data table component,
 * which is responsible for exporting the data to a CSV file.
 *
 * @return {void}
 */
function exportCSV() {
    // Call the exportCSV method of the data table component to export the data to a CSV file.
    dt.value.exportCSV();
}


/**
 * Megerősíti a kiválasztott termékek törlését.
 *
 * Ez a funkció akkor hívódik meg, ha a felhasználó törölni szeretné a kiválasztott termékeket.
 * A deleteProductsDialog változó értékét igazra állítja, ami
 * megnyílik egy megerősítő párbeszédablak a kiválasztott termékek törléséhez.
 *
 * @return {void}
 */
function confirmDeleteSelected() {
    // Állítsa a deleteProductsDialog változó értékét igazra,
    // amely megnyitja a megerősítő párbeszédablakot a kiválasztott termékek törléséhez.
    deleteProductsDialog.value = true;
}

/**
 * Az adott állapothoz tartozó megfelelő címkét adja vissza.
 *
 * Ez a függvény egy állapotot vesz fel argumentumként, és az állapoton alapuló címkét ad vissza.
 * A rendelkezésre álló állapotok: 'INSTOCK', 'LOWSTOCK' és 'OUTOFSTOCK'.
 *
 * @param {string} status - Egy termék állapota.
 * @return {string|null} Az állapot címkéje vagy nulla, ha az állapotot nem ismeri fel a rendszer.
 */
function getStatusLabel(status) {
    // Switch utasítás segítségével határozza meg az adott állapothoz tartozó megfelelő címkét.
    switch (status) {
        case 'INSTOCK':
            // Ha az állapot „KÉSZLET”, adja vissza a „siker” értéket.
            return 'success';
        case 'LOWSTOCK':
            // Ha az állapot 'LOWSTOCK', adja vissza a 'warn' értéket.
            return 'warn';
        case 'OUTOFSTOCK':
            // Ha a státusz 'OUT OF STOCK', adja vissza a 'veszély' értéket.
            return 'danger';
        default:
            // Ha a rendszer nem ismeri fel az állapotot, adja vissza nullát.
            return null;
    }
}

</script>

<template>
    <AppLayout>

        <div>
            <div class="card">
                
                <Toolbar class="md-6">
                    <template #start>
                        <Button label="New" icon="pi pi-plus" severity="secondary" class="mr-2" 
                                @click="openNew" />
                        <Button label="Delete" icon="pi pi-trash" severity="secondary" 
                                @click="confirmDeleteSelected"
                            :disabled="!selectedProducts || !selectedProducts.length" />
                    </template>

                    <template #end>
                        <Button label="Export" icon="pi pi-upload" severity="secondary" 
                                @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    v-model:selection="selectedProducts"
                    :value="products"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
                >
                    <template #header>
                        <div class="flex flex-wrap gap-2 items-center justify-between">
                            <h4 class="m-0">Manage Products</h4>
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filters['global'].value" placeholder="Search..." />
                            </IconField>
                        </div>
                    </template>

                    <Column selectionMode="multiple" style="width: 3rem" :exportable="false"></Column>
                    <Column field="code" header="Code" sortable style="min-width: 12rem"></Column>
                    <Column field="name" header="Name" sortable style="min-width: 16rem"></Column>
                    <Column header="Image">
                        <template #body="slotProps">
                            <img :src="`https://primefaces.org/cdn/primevue/images/product/${slotProps.data.image}`" :alt="slotProps.data.image" class="rounded" style="width: 64px" />
                        </template>
                    </Column>
                    <Column field="price" header="Price" sortable style="min-width: 8rem">
                        <template #body="slotProps">
                            {{ formatCurrency(slotProps.data.price) }}
                        </template>
                    </Column>
                    <Column field="category" header="Category" sortable style="min-width: 10rem"></Column>
                    <Column field="rating" header="Reviews" sortable style="min-width: 12rem">
                        <template #body="slotProps">
                            <Rating :modelValue="slotProps.data.rating" :readonly="true" />
                        </template>
                    </Column>
                    <Column field="inventoryStatus" header="Status" sortable style="min-width: 12rem">
                        <template #body="slotProps">
                            <Tag :value="slotProps.data.inventoryStatus" :severity="getStatusLabel(slotProps.data.inventoryStatus)" />
                        </template>
                    </Column>
                    <Column :exportable="false" style="min-width: 12rem">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" outlined rounded class="mr-2" @click="editProduct(slotProps.data)" />
                            <Button icon="pi pi-trash" outlined rounded severity="danger" @click="confirmDeleteProduct(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>
            </div>

            <!-- edit product dialog -->
            <Dialog v-model:visible="productDialog" :style="{ width: '450px' }" 
                    header="Product Details" :modal="true">
                <div class="flex flex-col gap-6">
                    <!-- product image -->
                    <img v-if="product.image"
                        :src="`https://primefaces.org/cdn/primevue/images/product/${product.image}`"
                        :alt="product.image" class="block m-auto pb-4" />
                    
                    <!-- product name -->
                    <div>
                        <label for="name" class="block font-bold mb-3">Name</label>
                        <InputText id="name" v-model.trim="product.name" required="true" autofocus
                            :invalid="submitted && !product.name" fluid />
                        <small v-if="submitted && !product.name" class="text-red-500">Name is required.</small>
                    </div>

                    <!-- product description -->
                    <div>
                        <label for="description" class="block font-bold mb-3">Description</label>
                        <Textarea id="description" v-model="product.description" required="true" rows="3" cols="20"
                            fluid />
                    </div>

                    <!-- product inventory status -->
                    <div>
                        <label for="inventoryStatus" class="block font-bold mb-3">Inventory Status</label>
                        <Select id="inventoryStatus" v-model="product.inventoryStatus" :options="statuses"
                            optionLabel="label" placeholder="Select a Status" fluid></Select>
                    </div>

                    <!-- product category -->
                    <div>
                        <span class="block font-bold mb-4">Category</span>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton id="category1" v-model="product.category" name="category"
                                    value="Accessories" />
                                <label for="category1">Accessories</label>
                            </div>
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton id="category2" v-model="product.category" name="category"
                                    value="Clothing" />
                                <label for="category2">Clothing</label>
                            </div>
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton id="category3" v-model="product.category" name="category"
                                    value="Electronics" />
                                <label for="category3">Electronics</label>
                            </div>
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton id="category4" v-model="product.category" name="category"
                                    value="Fitness" />
                                <label for="category4">Fitness</label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">

                        <!-- product price -->
                        <div class="col-span-6">
                            <label for="price" class="block font-bold mb-3">Price</label>
                            <InputNumber id="price" v-model="product.price" mode="currency" currency="USD"
                                locale="en-US" fluid />
                        </div>

                        <!-- product quantity -->
                        <div class="col-span-6">
                            <label for="quantity" class="block font-bold mb-3">Quantity</label>
                            <InputNumber id="quantity" v-model="product.quantity" integeronly fluid />
                        </div>
                    </div>
                </div>
                <template #footer>
                    <Button label="Cancel" icon="pi pi-times" text @click="hideDialog" />
                    <Button label="Save" icon="pi pi-check" @click="saveProduct" />
                </template>
            </Dialog>

            <!-- delete product dialog -->
            <Dialog v-model:visible="deleteProductDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="product">Are you sure you want to delete <b>{{ product.name }}</b>?</span>
                </div>
                <template #footer>
                    <Button label="No" icon="pi pi-times" text @click="deleteProductDialog = false" />
                    <Button label="Yes" icon="pi pi-check" @click="deleteProduct" />
                </template>
            </Dialog>

            <!-- delete products dialog -->
            <Dialog v-model:visible="deleteProductsDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="product">Are you sure you want to delete the selected products?</span>
                </div>
                <template #footer>
                    <Button label="No" icon="pi pi-times" text @click="deleteProductsDialog = false" />
                    <Button label="Yes" icon="pi pi-check" text @click="deleteSelectedProducts" />
                </template>
            </Dialog>

        </div>
    </AppLayout>
</template>