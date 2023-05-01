<script setup>
import {onMounted, onUpdated, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import EditProduct from "@/Components/Products/EditProduct.vue";
import { initFlowbite } from 'flowbite';

const props = defineProps({
    products: {
        type: Array,
    },
    categories: {
        type: Array,
        default: [],
    },
})
let productId = ref(null);
const confirmingProductDeletion = ref(null);
let currentProduct = ref({});
let openEditFormProduct = ref(false);
const emit = defineEmits(['eventSuccess']);

const closeModalProductDelete = () => {
    productId.value = null;
    confirmingProductDeletion.value = false;
};
const confirmProductDeletion = (id) => {
    productId.value = id;
    confirmingProductDeletion.value = true;
};
const deleteProduct = () => {
    const product = props.products.find(Product => Product.id === productId.value);
    useForm({}).delete(route('product.delete', productId.value), {
        onSuccess: () => {
            closeModalProductDelete();
            emit('eventSuccess', `Product <b>${product.name}</b> was deleted`);
        }
    });
};
const deleteImage = (productId) => {
    useForm({}).post(route('product.image-destroy', productId), {
        onSuccess: () => {
            emit('eventSuccess', 'Image deleted');
        },
    })
};
const openingEditFormProduct = (product) => {
    currentProduct = product;
    openEditFormProduct.value = true;
};
const closeEditFormProduct = () => {
    openEditFormProduct.value = false;
    currentProduct = {};
};
const eventSuccess = (mes) => {
    closeEditFormProduct();
    openEditFormProduct.value = false;
    if (!!mes) {
        emit('eventSuccess', mes);
    }
};
onMounted(() => {
    initFlowbite();
})
onUpdated(() => {
    initFlowbite();
})
</script>

<template>

    <v-table
        hover
        fixed-header
    >
        <thead>
        <tr>
            <th class="text-left">
                Image
            </th>
            <th class="text-left">
                Product Name
            </th>
            <th class="text-left">
                Description
            </th>
            <th class="text-left">
                Category
            </th>
            <th class="text-left">
                Price
            </th>
            <th class="text-left">
                Quantity
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
            <td>
                <v-img
                    v-show="product.image"
                    :src="product.image"
                    class="h-50"
                />
                <v-btn
                    v-show="product.image"
                    variant="outlined"
                    size="x-small"
                    color="warning"
                    @click="deleteImage(product.id)"
                >
                    Delete Image
                </v-btn>
            </td>
            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>
                <span
                    v-if="product.categories.length"
                    v-for="category in product.categories"
                >{{category.name}}<br /></span>
                <span v-else></span>
            </td>
            <td>{{ product.price }}</td>
            <td>{{ product.quantity }}</td>
            <td>{{ new Date(product.created_at).toLocaleString() }}</td>
            <td>
                <div class="grid">
                    <v-btn
                        variant="outlined"
                        size="x-small"
                        @click="openingEditFormProduct(product)"
                    >
                        Edit product
                    </v-btn>
                    <v-btn
                        variant="outlined"
                        size="x-small"
                        @click="confirmProductDeletion(product.id)"
                    >
                        Delete Product
                    </v-btn>
                </div>
            </td>
        </tr>
        </tbody>
    </v-table>

    <Modal :show="confirmingProductDeletion" @close="closeModalProductDelete">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this product?
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                This action is irreversible.
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModalProductDelete"> Cancel </SecondaryButton>
                <DangerButton
                    class="ml-3"
                    @click="deleteProduct"
                >
                    Delete Product
                </DangerButton>
            </div>
        </div>
    </Modal>

    <Modal :show="openEditFormProduct">
        <EditProduct
            :show="openEditFormProduct"
            :categories="categories"
            :product="currentProduct"
            @closeEditForm="closeEditFormProduct"
            @eventSuccess="eventSuccess"
        />
    </Modal>
</template>

<style scoped>

</style>
