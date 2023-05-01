<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {computed, reactive, ref} from "vue";
import Notification from "@/Components/Notification.vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { onMounted } from 'vue'
import { initFlowbite } from 'flowbite'
import EditStoreDescription from "@/Components/Stores/EditStoreDescription.vue";

const props = defineProps(reactive({
    stores: {
        type: Array,
    },
}));
let modalEvents = ref(null);
let message = ref('');
let confirmingStoreEvent = ref(false);
let storeId = ref(null);
let eventMessage = ref('');
let openingEditDescription = ref(false);

const openEditDescription = (id) => {
    storeId.value = id;
    openingEditDescription.value = true;
}
const closeEditDescription = () => {
    openingEditDescription.value = false;
}

const changeVisibleStore = (storeId) => {
    const Store = props.stores.find(store => store.store_id === storeId);
    useForm({}).put(route('store.active', storeId), {
        onSuccess: () => {
            eventSuccess(`Store <strong>"${Store.name_store}"</strong> was updated`);
        },
    });
}

const eventSuccess = (mes) => {
    closeEditDescription();
    message.value = mes;
    if (!!mes) {
        modalEvents.value.openNotification();
    }
}

const confirmStoreDeletion = (id) => {
    confirmingStoreEvent.value = true;
    storeId.value = id;
    eventMessage.value = 'delete';
}

const closeModalStoreEvent = () => {
    confirmingStoreEvent.value = false;
    storeId.value = null;
    eventMessage.value = '';
}

const confirmCleanUp = (id) => {
    confirmingStoreEvent.value = true;
    storeId.value = id;
    eventMessage.value = 'clear';
}

const deleteStore = (store) => {
    useForm({}).delete(route('store.delete', store.store_id), {
        onSuccess: () => {
            eventSuccess(`Shop <strongb>${store.name_store}</strongb> and its owner was deleted`);
            closeModalStoreEvent();
        },
    });
}

const clearStore = (store) => {
    useForm({}).put(route('store.clear', store.store_id), {
        onSuccess: () => {
            eventSuccess(`Shop <strongb>${store.name_store}</strongb> was cleared`);
            closeModalStoreEvent();
        },
    });
}

const chooseAction = () => {
    const store = props.stores.find(Store => Store.store_id === storeId.value);
    eventMessage.value === 'delete' ? deleteStore(store) : clearStore(store);
}

// Initialize components based on data attribute selectors
onMounted(() => {
    initFlowbite();
})

const store = computed(() => props.stores.find(Store => Store.store_id === storeId.value));
</script>

<template>
    <Head><title>All stores</title></Head>
    <AuthenticatedLayout>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Store Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Client
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Products count
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Activity
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Created
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600"
                        v-for="store in stores"
                        :key="store.id"
                    >
                        <th class="px-6 py-4">
                            <a
                                :href="route('store.show', store.store_id)"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                data-tooltip-target="tooltip-show-store"
                            >
                                {{ store.store_id }}
                            </a>
                        </th>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <a
                                :href="route('store.show', store.store_id)"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                data-tooltip-target="tooltip-show-store"
                            >
                                {{ store.name_store }}
                            </a>
                        </td>
                        <td class="px-6 py-4 cursor-pointer">
                            <span
                                @click="openEditDescription(store.store_id)"
                                data-tooltip-target="tooltip-edit-description"
                            >
                                {{ store.description }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ store.client ? store.client.name : '' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ store.products ? store.products.length : 0 }}
                        </td>
                        <td
                            @click="changeVisibleStore(store.store_id)"
                            class="px-6 py-4 hover:bg-gray-100 cursor-pointer hover:bg-blue-100"
                            data-tooltip-target="tooltip-default"
                        >
                            <svg
                                v-if="store.is_active"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-6 h-6 mx-auto"
                                data-tooltip-target="tooltip-default"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-6 h-6 mx-auto"
                                data-tooltip-target="tooltip-default"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </td>
                        <td class="px-6 py-4">
                            {{ new Date(store.created_at).toLocaleString() }}
                        </td>
                        <td class="px-6 py-4">
                            <button
                                @click="confirmStoreDeletion(store.store_id)"
                                type="button"
                                class="text-uppercase px-2 py-1 text-xs font-medium text-center focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 rounded-md dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                            >
                                Delete Store
                            </button>
                            <button
                                @click="confirmCleanUp(store.store_id)"
                                type="button"
                                class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-md text-sm px-2 py-1 mt-1 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                            >
                                Clean up Store
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="tooltip-default" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Click on icon to change store availability.
            <div data-popper-arrow></div>
        </div>
        <div id="tooltip-show-store" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Click to see Store's admin page.
            <div data-popper-arrow></div>
        </div>
        <div id="tooltip-edit-description" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            Click to change store's description text.
            <div data-popper-arrow></div>
        </div>

        <Modal :show="confirmingStoreEvent" @close="closeModalStoreEvent">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to {{eventMessage}} this store?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    This action is irreversible.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalStoreEvent"> Cancel </SecondaryButton>
                    <DangerButton
                        class="ml-3"
                        @click="chooseAction"
                    >
                        Yes, {{eventMessage}} Store
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <Notification :message="message" ref="modalEvents" />

        <Modal :show="openingEditDescription">
            <EditStoreDescription
                :show="openingEditDescription"
                :store="store"
                @closeEditDescription="closeEditDescription"
                @eventSuccess="eventSuccess"
            />
        </Modal>

    </AuthenticatedLayout>
</template>

<style scoped>

</style>
