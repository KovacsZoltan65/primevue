<script setup>
import { ref, reactive, onMounted } from "vue";
import ErrorService from "@/service/ErrorService";
import { Paginator } from "primevue";

const logs = ref([]);
const pagination = reactive({
    currentPage: 1,
    perPage: 10,
    totalRecords: 0,
    totalPages: 0,
});

const fetchLogs = async () => {
    ErrorService.getLogs(pagination.currentPage, pagination.perPage)
        .then((response) => {
            logs.value = response.data.logs;
            pagination.totalRecords = response.data.total;
            pagination.totalPages = response.data.last_page;
        })
        .catch((error) => {
            console.error("Hibalista lekérdezés hiba:", error);
        })
        .finally(() => {
            //
        });
};

const onPageChange = (event) => {
    pagination.currentPage = event.page + 1; // PrimeVue 0-indexed
    fetchLogs();
};

const routeTemplate = (rowData) => {
    return rowData.properties.route || "-";
};

const actionTemplate = (rowData) => {
    return `
        <button
            class="p-button p-component p-button-sm"
            @click="viewLog(rowData.id)"
        >
            Részletek
        </button>
  `;
};

const viewLog = (id) => {
    // Irányítsa a részletek megtekintésére
    window.location.href = `/error-logs/${id}`;
};

onMounted(fetchLogs);

</script>
<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Hibák listája</h1>

        <DataTable
            :paginator="true"
            :value="logs.data"
            :rows="pagination.perPage"
            responsiveLayout="scroll"
            @page="onPageChange"
        >
            <Column field="created_at" header="Időpont"></Column>
            <Column field="description" header="Leírás"></Column>
            <Column
                field="properties.route"
                header="Útvonal"
                :body="routeTemplate"
            />
            <Column
                header="Műveletek"
                :body="actionTemplate"
                style="text-align: center"
            />
        </DataTable>

        <Paginator
            v-if="pagination.totalPages > 1"
            :totalRecords="pagination.totalRecords"
            :rows="pagination.perPage"
            :first="pagination.currentPage * pagination.perPage - pagination.perPage"
            @page="onPageChange"
        />

    </div>
  </template>
