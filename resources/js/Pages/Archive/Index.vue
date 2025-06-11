<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

// --- PrimeVue Components ---
import DataTable, { type DataTablePageEvent, type DataTableSortEvent } from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Sidebar from 'primevue/sidebar';
import Checkbox from 'primevue/checkbox';

// --- TypeScript Interfaces ---
interface Archive {
    id: number;
    archive_number_formatted: string;
    archive_name: string;
    archive_status: string;
    skkad: string;
    archive_type: string;
    properties: string;
}

interface FilterOptions {
    filecodes: { id: number; file_code: string; code_name: string }[];
    types: { id: number; archive_type: string }[];
    properties: string[];
}

// --- Props ---
const props = defineProps<{
    archives: {
        data: Archive[];
        total: number;
        current_page: number;
        per_page: number;
    };
    filters: {
        search: string | null;
        filecodes: number[] | null;
        types: number[] | null;
        properties: string[] | null;
        sortField: string | null;
        sortOrder: 'asc' | 'desc' | null;
    };
    filterOptions: FilterOptions;
}>();


// --- State Management ---
const isFilterSidebarVisible = ref(false);

const localFilters = ref({
    search: props.filters.search || '',
    filecodes: props.filters.filecodes || [],
    types: props.filters.types || [],
    properties: props.filters.properties || [],
});

const sort = ref({
    field: props.filters.sortField || 'date_input',
    order: props.filters.sortOrder === 'desc' ? -1 : 1,
});


/**
 * Core function to load data from the server with all current settings.
 */
const loadArchives = (options: { page: number; sortField: string; sortOrder: number; }) => {
    const queryParams: Record<string, any> = {
        page: options.page,
        sort: options.sortField,
        direction: options.sortOrder === 1 ? 'asc' : 'desc',
    };

    // Add all filters from our local state
    if (localFilters.value.search) {
        queryParams.search = localFilters.value.search;
    }
    if (localFilters.value.filecodes.length > 0) {
        queryParams.filecodes = localFilters.value.filecodes;
    }
    if (localFilters.value.types.length > 0) {
        queryParams.types = localFilters.value.types;
    }
    if (localFilters.value.properties.length > 0) {
        queryParams.properties = localFilters.value.properties;
    }

    router.get('/archives', queryParams, {
        preserveState: true,
        replace: true,
    });
};

// --- Event Handlers ---

const onPage = (event: DataTablePageEvent) => {
    loadArchives({
        page: event.page + 1, // PrimeVue is 0-indexed
        sortField: sort.value.field,
        sortOrder: sort.value.order,
    });
};

const onSort = (event: DataTableSortEvent) => {
    sort.value.field = event.sortField as string;
    sort.value.order = event.sortOrder as number;
    loadArchives({
        page: props.archives.current_page,
        sortField: sort.value.field,
        sortOrder: sort.value.order,
    });
};

const applyAllFilters = () => {
    // When applying filters, always go back to the first page.
    loadArchives({
        page: 1,
        sortField: sort.value.field,
        sortOrder: sort.value.order,
    });
    isFilterSidebarVisible.value = false; // Close the sidebar after applying
};

// This watcher ensures the local state is updated if the props change from the server.
watch(() => props.filters, (newFilters) => {
    localFilters.value.search = newFilters.search || '';
    localFilters.value.filecodes = newFilters.filecodes || [];
    localFilters.value.types = newFilters.types || [];
    localFilters.value.properties = newFilters.properties || [];
    sort.value.field = newFilters.sortField || 'date_input';
    sort.value.order = newFilters.sortOrder === 'desc' ? -1 : 1;
}, { deep: true });

</script>

<template>
    <div class="card p-4 md:p-8">
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Daftar Arsip Publik</h1>
            <p class="text-gray-600 mt-1">Gunakan pencarian dan filter untuk menemukan arsip yang Anda butuhkan.</p>
        </header>

        <DataTable
            :value="props.archives.data"
            :totalRecords="props.archives.total"
            :rows="props.archives.per_page"
            :first="(props.archives.current_page - 1) * props.archives.per_page"
            :sortField="sort.field"
            :sortOrder="sort.order"
            lazy
            paginator
            @page="onPage"
            @sort="onSort"
            tableClass="min-w-full divide-y divide-gray-200"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
            currentPageReportTemplate="Menampilkan {first} sampai {last} dari {totalRecords} arsip"
        >
            <template #header>
                <div class="flex justify-between items-center gap-2">
                    <Button
                        icon="pi pi-filter"
                        label="Filter"
                        outlined
                        @click="isFilterSidebarVisible = true"
                    />
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" />
                        <InputText
                            v-model="localFilters.search"
                            placeholder="Cari... & Tekan Enter"
                            @keydown.enter="applyAllFilters"
                        />
                    </span>
                </div>
            </template>

            <template #empty>
                <div class="text-center p-8">
                    Tidak ada data ditemukan.
                </div>
            </template>

            <Column field="archive_number_formatted" header="Nomor Arsip" sortable :sortField="'archive_number'"></Column>
            <Column field="archive_name" header="Nama Arsip" sortable></Column>
            <Column field="properties" header="Sifat" sortable></Column>
            <Column field="archive_status" header="Status" sortable></Column>
            <Column field="archive_type" header="Jenis" sortable></Column>

        </DataTable>
    </div>

    <!-- Filter Sidebar -->
    <Sidebar v-model:visible="isFilterSidebarVisible" header="Filter Arsip" class="w-full md:w-1/3 lg:w-1/4">
        <div class="p-4 space-y-6">
            <div>
                <h4 class="font-semibold mb-3">Kode File</h4>
                <div class="flex flex-col gap-2 max-h-48 overflow-y-auto">
                    <div v-for="filecode in props.filterOptions.filecodes" :key="filecode.id" class="flex items-center">
                        <Checkbox v-model="localFilters.filecodes" :inputId="`fc-${filecode.id}`" :name="`fc`" :value="filecode.id" />
                        <label :for="`fc-${filecode.id}`" class="ml-2"> {{ filecode.file_code }} ({{ filecode.code_name }}) </label>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Jenis Arsip</h4>
                <div class="flex flex-col gap-2">
                    <div v-for="type in props.filterOptions.types" :key="type.id" class="flex items-center">
                        <Checkbox v-model="localFilters.types" :inputId="`type-${type.id}`" :name="`type`" :value="type.id" />
                        <label :for="`type-${type.id}`" class="ml-2"> {{ type.archive_type }} </label>
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-semibold mb-3">Sifat Arsip</h4>
                <div class="flex flex-col gap-2">
                    <div v-for="prop in props.filterOptions.properties" :key="prop" class="flex items-center">
                        <Checkbox v-model="localFilters.properties" :inputId="`prop-${prop}`" :name="`prop`" :value="prop" />
                        <label :for="`prop-${prop}`" class="ml-2"> {{ prop }} </label>
                    </div>
                </div>
            </div>
        </div>
        <template #footer>
            <div class="p-4">
                <Button label="Terapkan Filter" @click="applyAllFilters" class="w-full" />
            </div>
        </template>
    </Sidebar>
</template>

<style>
/* PrimeVue uses its own class names. You can override them here if needed. */
.p-datatable-header {
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}
</style>
