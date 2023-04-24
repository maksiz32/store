<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddCategories from "@/Pages/Stores/AddCategories.vue";
import {ref} from "vue";
import { Head } from '@inertiajs/vue3';
import AddProducts from "@/Pages/Stores/AddProducts.vue";

const props = defineProps({
    store: {
        type: Object,
    },
    products: {
        type: Object,
    },
});

let snackbar = ref(false);
let messageSuccess = ref('');

const addProductSuccess = (mes) => {
    messageSuccess.value = mes;
    snackbar.value = (!!mes);
}
const addCatSuccess = (mes) => {
    messageSuccess.value = mes;
    snackbar.value = (!!mes);
}

</script>

<template>
    <Head><title>{{ store.name }}</title></Head>

    <AuthenticatedLayout>
        <section class="flex justify-space-around">
            <AddCategories @addCatSuccess="addCatSuccess" />
            <AddProducts @addProductSuccess="addProductSuccess" :categories="store.categories"/>
            <AddCategories />
        </section>

        <v-table
            hover
            fixed-header
        >
            <thead>
            <tr>
                <th class="text-left">
                    ID
                </th>
                <th class="text-left">
                    Image
                </th>
                <th class="text-left">
                    Product Name
                </th>
                <th class="text-left">
                    Role
                </th>
                <th class="text-left">
                    Store name
                </th>
                <th class="text-left">
                    Created
                </th>
                <th class="text-left">
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="product in products"
                :key="product.id"
            >
                <td>{{ product.id }}</td>
                <td><v-img :src="product.image"/></td>
                <td>{{ product.name }}</td>
                <td>{{ product.price }}</td>
                <td>{{ product.quantity }}</td>
                <td>{{ new Date(product.created_at).toLocaleString() }}</td>
                <td justify="center">
                    <v-btn variant="outlined" size="x-small" :href="route('dashboard')">
                        Manage Client
                    </v-btn>
                    <v-btn variant="outlined" size="x-small" :href="route('dashboard')">
                        Delete Store
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-table>

        <v-snackbar
            v-model="snackbar"
            color="grey-lighten-1"
        >
            {{ messageSuccess }}
            <template v-slot:actions>
                <v-btn
                    class="font-weight-black"
                    color="red"
                    variant="text"
                    @click="snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
