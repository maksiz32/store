<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddCategory from "@/Components/Categories/AddCategory.vue";
import {onUpdated, reactive, ref} from "vue";
import {Head, useForm, usePage} from '@inertiajs/vue3';
import AddProducts from "@/Components/Products/AddProducts.vue";
import Notification from "@/Components/Notification.vue";
import Modal from '@/Components/Modal.vue';
import { onMounted } from 'vue';
import { initFlowbite } from 'flowbite';
import EditUser from "@/Components/Users/EditUser.vue";
import EditStoreName from "@/Components/Stores/EditStoreName.vue";
import ProductsTable from "@/Components/Products/ProductsTable.vue";

const props = defineProps({
    store: reactive({
        type: Object,
    }),
    products: {
        type: Array,
    },
    categories: {
        type: Array,
        default: [],
    },
});

let message = ref('');
const modalEvents = ref(null);
const emit = defineEmits(['eventSuccess', 'deleteProductSuccess']);
let openEditFormUser = ref(false);
let openEditStoreName = ref(false);
const roleId = usePage().props.auth.user.users_role_id;

const changeVisibleStore = (storeId) => {
    // Only for admins
    if (roleId === 20) {
        useForm({}).put(route('store.active', storeId), {
            onSuccess: () => {
                eventSuccess(`Store <strong>"${props.store.name_store}"</strong> was updated`);
            },
        });
    }
}
const eventSuccess = (mes) => {
    openEditFormUser.value = false;
    message.value = mes;
    if (!!mes) {
        modalEvents.value.openNotification();
    }
};
const closeEditFormStoreName = () => {
    openEditStoreName.value = false;
};

onMounted(() => {
    initFlowbite();
})
onUpdated(() => {
    initFlowbite();
})
</script>

<template>
    <Head><title v-if="store">{{ store.name }}</title></Head>

    <AuthenticatedLayout>
        <main class="container mx-auto m-3">
            <section v-if="store">
                <h2
                    class="cursor-pointer text-center mb-4 text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-3xl lg:text-4xl dark:text-white"
                >
                    <span
                        @click="openEditStoreName = true"
                        data-tooltip-target="tooltip-change-store-name"
                    >
                        {{ store.name_store }}
                    </span>
                    <span v-if="roleId === 20">
                        <span
                            v-if="store.is_active"
                            @click="changeVisibleStore(store.store_id)"
                            data-tooltip-target="tooltip-change-activity"
                            class="align-super cursor-pointer bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300"
                        >
                            &#10004;&nbsp;Active
                        </span>
                        <span
                            v-else
                            @click="changeVisibleStore(store.store_id)"
                            data-tooltip-target="tooltip-change-activity"
                            class="align-super cursor-pointer bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
                        >
                            &#10008;&nbsp;Not active
                        </span>
                    </span>
                </h2>
                <div class="mb-4 py-3 px-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h5
                        class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >
                        Owner:
                    </h5>
                    <div
                        class="flex font-normal text-gray-700 dark:text-gray-400"
                    >
                        <span class="w-1/3">
                            <strong>Name:</strong> {{ store.client.name }}
                        </span>
                        <span class="w-1/3">
                            <strong>E-mail:</strong> {{ store.client.email }}
                        </span>
                        <span class="w-1/3">
                            <strong>Registration:</strong> {{ new Date(store.client.created_at).toLocaleString() }}
                        </span>
                    </div>
                    <button
                        type="button"
                        class="my-2 text-uppercase text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        @click="openEditFormUser = true"
                    >
                        Edit Owner
                    </button>
                </div>
                <div class="mb-4 py-3 px-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h5
                        class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >
                        Categories:
                    </h5>
                    <div
                        class="flex font-normal text-gray-700 dark:text-gray-400 mb-2 p-2"
                        v-if="categories.length"
                    >
                        <span
                            class="flex items-center text-sm font-medium text-gray-900 dark:text-white mx-2"
                            v-for="category in categories"
                        >
                            <span
                                v-if="category.is_show_nav"
                                class="flex w-2.5 h-2.5 bg-blue-600 rounded-full mr-1.5 flex-shrink-0"
                            ></span>
                            <span
                                v-else
                                class="flex w-2.5 h-2.5 bg-gray-600 rounded-full mr-1.5 flex-shrink-0"
                            ></span>
                            {{ category.name }}
                        </span>
                    </div>
                    <div
                        v-else
                        class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                        role="alert"
                    >
                        <span class="font-medium">No categories</span> in the store.
                    </div>
                    <AddCategory :store_id="store.store_id" @eventSuccess="eventSuccess" />
                </div>
                <div
                    class="mb-4 py-3 px-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700"
                >
                    <h5
                        class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                    >
                        Products:
                    </h5>
                    <div
                        class="flex font-normal text-gray-700 dark:text-gray-400 mb-3"
                        v-if="products.length"
                    >
                        <ProductsTable
                            @eventSuccess="eventSuccess"
                            :products="products"
                            :categories="categories"
                        />
                    </div>
                    <div
                        v-else
                        class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
                        role="alert"
                    >
                        <span class="font-medium">No products</span> in the store.
                    </div>
                    <AddProducts
                        @eventSuccess="eventSuccess"
                        :store_id="store.store_id"
                        :categories="store.categories"
                    />
                </div>
            </section>
            <section v-else>
                No <span class="font-medium">data</span> request.
            </section>
        </main>
        <Notification :message="message" ref="modalEvents" />
    </AuthenticatedLayout>

    <div
        id="tooltip-change-store-name"
        role="tooltip"
        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
    >
        Click here to change store name.
        <div data-popper-arrow></div>
    </div>

    <div
        id="tooltip-change-activity"
        role="tooltip"
        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
    >
        Click to change status activity.
        <div data-popper-arrow></div>
    </div>

    <Modal :show="openEditStoreName">
        <EditStoreName
            :show="openEditStoreName"
            :store="store"
            @closeEditFormStoreName="closeEditFormStoreName"
            @eventSuccess="eventSuccess"
        />
    </Modal>

    <Modal :show="openEditFormUser">
        <EditUser
            :show="openEditFormUser"
            :user="store.client"
            @closeEditForm="openEditFormUser = false"
            @eventSuccess="eventSuccess"
        />
    </Modal>
</template>

<style scoped>

</style>
