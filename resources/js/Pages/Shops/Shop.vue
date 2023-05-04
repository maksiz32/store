<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from "axios";
import {computed, reactive, ref} from "vue";
import { store } from '@/store/store.js';

const props = defineProps({
    shop: {
        type: Object,
    },
    categories: {
        type: Array,
        default: [],
    },
    products: reactive({
        type: Array,
        default: [],
    }),
    category_id: {
        type: Number,
        default: 0,
    },
});
let prods = ref(props.products);
let category_id = ref(props.category_id);

const getProductByCategories = async (categoryId) => {
    category_id.value = categoryId;
    await axios.get(route('ajax-store.show', {
        client_id: props.shop.store_id,
        category_id: categoryId,
    }))
    .then(Response => {
        prods.value = Response.data.products;
    })
};
const addInBasket = (product) => {
    store.addProduct(product);
}
const categoryName = computed(
    () => {
        const category = props.categories.find(cat => cat.id === category_id.value);
        return category ? category.name : null;
    }
)
</script>

<template>
    <Head><title>Store: {{ shop.name_store }}</title></Head>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="p-6">
                <h2 class="mb-6 text-3xl font-bold text-gray-900">{{ shop.name_store }}</h2>
                <nav aria-label="Breadcrumb">
                    <ol role="list" class="mx-auto flex max-w-2xl items-center space-x-2 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                        <li>
                            <div class="flex items-center">
                                <a
                                    :href="route('shop.category', parseInt(shop.store_id, 10))"
                                    class="mr-2 text-sm font-medium text-gray-900"
                                >
                                    {{ shop.name_store }}
                                </a>
                                <svg v-if="category_id > 0" width="16" height="20" viewBox="0 0 16 20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
                                    <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
                                </svg>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span v-if="category_id > 0" class="mr-2 text-sm font-medium text-gray-500">{{ categoryName }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div
                    v-if="categories && categories.length"
                    class="flex items-center justify-center py-2 md:py-3 flex-wrap"
                >
                    <button
                        type="button"
                        @click.prevent="getProductByCategories(0)"
                        class="text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:bg-gray-900 dark:focus:ring-blue-800"
                        :class="{'v-btn--disabled':category_id===0}"
                    >
                        All categories
                    </button>
                    <button
                        v-for="category in categories"
                        :key="category.id"
                        @click.prevent="getProductByCategories(category.id)"
                        type="button"
                        class="text-blue-700 hover:text-white border border-blue-600 bg-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full text-base font-medium px-5 py-2.5 text-center mr-3 mb-3 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:bg-gray-900 dark:focus:ring-blue-800"
                        :class="{'v-btn--disabled':category_id===category.id}"
                    >
                        {{ category.name }}
                    </button>
                </div>

                <div
                    class="grid grid-cols-2 md:grid-cols-3 gap-4 my-2 px-2"
                    v-if="products && prods.length"
                >
                    <div
                        v-for="product in prods"
                        class="h-[25rem] w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
                    >
                        <a :href="route('product.show', {client_id: shop.store_id, category_id: category_id > 0 ? category_id : 0, product_id: product.id})">
                            <img
                                v-if="product.image"
                                class="p-8 rounded-t-lg max-h-[60%] w-full object-cover object-center"
                                :src="product.image" alt="product image"
                            />
                            <div v-else class="p-8 rounded-t-lg h-[60%] w-full object-cover object-center">
                                <svg class="w-[50%] h-[50%] mx-auto my-auto text-gray-200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" viewBox="0 0 640 512"><path d="M480 80C480 35.82 515.8 0 560 0C604.2 0 640 35.82 640 80C640 124.2 604.2 160 560 160C515.8 160 480 124.2 480 80zM0 456.1C0 445.6 2.964 435.3 8.551 426.4L225.3 81.01C231.9 70.42 243.5 64 256 64C268.5 64 280.1 70.42 286.8 81.01L412.7 281.7L460.9 202.7C464.1 196.1 472.2 192 480 192C487.8 192 495 196.1 499.1 202.7L631.1 419.1C636.9 428.6 640 439.7 640 450.9C640 484.6 612.6 512 578.9 512H55.91C25.03 512 .0006 486.1 .0006 456.1L0 456.1z"/></svg>
                            </div>
                        </a>
                        <div class="px-5 pb-5">
                            <a :href="route('product.show', {client_id: shop.store_id, category_id: category_id, product_id: product.id})">
                                <h5
                                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white"
                                >
                                    {{  product.name }}
                                </h5>
                            </a>
                            <div class="flex items-center mt-2.5 mb-5">
                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ml-3">5.0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">&#8362; {{ product.price }}</span>
                                <a
                                    :disabled="store.isLoading"
                                    @click="addInBasket(product)"
                                    href="#"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Add to cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="mx-auto my-6">
                    No data
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
