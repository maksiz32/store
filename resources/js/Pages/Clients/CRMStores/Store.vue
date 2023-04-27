<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddCategories from "@/Pages/Clients/CRMStores/AddCategories.vue";
import {ref} from "vue";
import {Head, useForm} from '@inertiajs/vue3';
import AddProducts from "@/Pages/Clients/CRMStores/AddProducts.vue";
import Notification from "@/Components/Notification.vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import EditProduct from "@/Pages/Clients/CRMStores/EditProduct.vue";

const props = defineProps({
    store: {
        type: Object,
    },
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
const confirmingProductDeletion = ref(null);
const emit = defineEmits(['eventSuccess', 'deleteProductSuccess']);
let productId = ref(null);
let openEditForm = ref(false);
let currentProduct = ref({});

const confirmProductDeletion = (id) => {
    productId.value = id;
    confirmingProductDeletion.value = true;
};

const deleteProduct = () => {
    const product = props.products.find(Product => Product.id === productId.value);
    useForm({}).delete(route('product.delete', productId.value), {
        onSuccess: () => {
            closeEditForm();
            eventSuccess(`Product <b>${product.name}</b> was deleted`);
        }
    });
};
const deleteImage = (productId) => {
    useForm({}).post(route('product.image-destroy', productId), {
        onSuccess: () => {
            eventSuccess('Image deleted');
        },
    })
}

const closeModalProductDelete = () => {
    productId.value = null;
    confirmingProductDeletion.value = false;
};
const eventSuccess = (mes) => {
    closeEditForm();
    message.value = mes;
    if (!!mes) {
        modalEvents.value.openNotification();
    }
}
const openingEditForm = (product) => {
    currentProduct = product;
    openEditForm.value = true;
}
const closeEditForm = () => {
    openEditForm.value = false;
    currentProduct = {};
};
</script>

<template>
    <Head><title v-if="store">{{ store.name }}</title></Head>

    <AuthenticatedLayout>
        <section class="flex justify-space-around" v-if="store">
            <AddCategories @eventSuccess="eventSuccess" />
            <AddProducts @eventSuccess="eventSuccess" :categories="store.categories"/>
        </section>
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
                    <v-img v-show="product.image" :src="product.image" class="store-crm__photo" />
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
                            @click="openingEditForm(product)"
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

        <Modal :show="openEditForm">
            <EditProduct
                :show="openEditForm"
                :categories="categories"
                :product="currentProduct"
                @closeEditForm="closeEditForm"
                @eventSuccess="eventSuccess"
            />
        </Modal>
        <Notification :message="message" ref="modalEvents" />
    </AuthenticatedLayout>
</template>

<style scoped>
.store-crm__photo {
    max-height: 80px;
}
</style>
