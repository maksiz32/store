<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddProducts from "@/Components/Products/AddProducts.vue";
import ProductsTable from "@/Components/Products/ProductsTable.vue";
import {Head} from '@inertiajs/vue3';
import {reactive, ref} from "vue";
import Notification from "@/Components/Notification.vue";

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
const modalEvents = ref(null);
let openEditFormUser = ref(false);
let message = ref('');

const eventSuccess = (mes) => {
    openEditFormUser.value = false;
    message.value = mes;
    if (!!mes) {
        modalEvents.value.openNotification();
    }
};
</script>

<template>
    <Head><title>Products</title></Head>

    <AuthenticatedLayout>
        <AddProducts
            @eventSuccess="eventSuccess"
            :store_id="store.store_id"
            :categories="store.categories"
        />
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
        <Notification :message="message" ref="modalEvents" />
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
