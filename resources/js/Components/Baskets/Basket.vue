<script setup>
import {store} from "@/store/store.js";

let props = defineProps({
    showingBasket: {
        type: Boolean,
        default: false,
    },
});
const emit = defineEmits(['closeBasketModal']);

const closeBasket = () => {
    emit('closeBasketModal');
}

const removeProductFromBasket = (product_id) => {
    store.removeFromBasket(product_id);
}

const remoteProduct = (product_id) => {
    store.remoteFromBasket(product_id);
}

const basketSubTotal = () => {
    let total = 0;
    store.basket.map(product => {
        total += (product.price * product.count)
    });

    return total;
}
</script>

<template>
    <div
        v-show="showingBasket"
        class="relative z-10 animated fadeIn fixed"
        aria-labelledby="slide-over-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Shopping cart</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button @click="closeBasket" type="button" class="-m-2 p-2 text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-8" v-if="store.basket.length">
                                    <div class="flow-root">
                                        <ul role="list" class="-my-6 divide-y divide-gray-200">
                                            <li
                                                class="flex py-6"
                                                v-for="product in store.basket"
                                            >
                                                <a :href="route('product-short.show', {product_id: product.id})">
                                                    <div
                                                        class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200"
                                                        v-if="product.image"
                                                    >
                                                            <img
                                                                :src="product.image"
                                                                alt=""
                                                                class="h-full w-full object-cover object-center"
                                                            >
                                                    </div>
                                                    <div
                                                        class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200"
                                                        v-else
                                                    >
                                                        <svg class="w-[80%] h-[80%] mx-auto my-auto text-gray-200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" fill="currentColor" viewBox="0 0 640 512"><path d="M480 80C480 35.82 515.8 0 560 0C604.2 0 640 35.82 640 80C640 124.2 604.2 160 560 160C515.8 160 480 124.2 480 80zM0 456.1C0 445.6 2.964 435.3 8.551 426.4L225.3 81.01C231.9 70.42 243.5 64 256 64C268.5 64 280.1 70.42 286.8 81.01L412.7 281.7L460.9 202.7C464.1 196.1 472.2 192 480 192C487.8 192 495 196.1 499.1 202.7L631.1 419.1C636.9 428.6 640 439.7 640 450.9C640 484.6 612.6 512 578.9 512H55.91C25.03 512 .0006 486.1 .0006 456.1L0 456.1z"/></svg>
                                                    </div>
                                                </a>
                                                <div class="ml-4 flex flex-1 flex-col">
                                                    <a :href="route('product-short.show', {product_id: product.id})">
                                                        <div>
                                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                                <h3>
                                                                    <a href="#">{{ product.name }}</a>
                                                                </h3>
                                                                <p class="ml-4">${{ product.price }}</p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                    <div class="flex flex-1 items-end justify-between text-sm">
                                                        <p class="text-gray-500">
                                                            Qty {{ product.count }}
                                                            <button
                                                                :disabled="store.isLoading"
                                                                type="button"
                                                                class="disabled:opacity-25 ml-2 text-xl px-2.5 focus:outline-none hover:bg-red-100 rounded-md border border-gray-200 hover:text-red-500 hover:bg-red-400 focus:z-10 focus:ring-4 focus:ring-gray-200"
                                                                @click.prevent="removeProductFromBasket(product.id)"
                                                            >-</button>
                                                            <button
                                                                :disabled="store.isLoading"
                                                                type="button"
                                                                class="disabled:opacity-25 ml-2 text-xl px-2 focus:outline-none hover:bg-red-100 rounded-md border border-gray-200 hover:text-red-500 hover:bg-red-400 focus:z-10 focus:ring-4 focus:ring-gray-200"
                                                                @click.prevent="store.addProduct(product)"
                                                            >+</button>
                                                        </p>

                                                        <div class="flex">
                                                            <button
                                                                type="button"
                                                                class="font-medium text-indigo-600 hover:text-indigo-500"
                                                                @click.prevent="remoteProduct(product.id)"
                                                            >
                                                                Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>Subtotal</p>
                                    <p>${{ basketSubTotal() }}</p>
                                </div>
                                <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                                <div class="mt-6">
                                    <a
                                        :disabled="basketSubTotal() <= 0"
                                        :href="basketSubTotal() > 0 ? route('order.index') : '#'"
                                        class="block cursor-pointer flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700"
                                    >
                                        Checkout
                                    </a>
                                </div>
                                <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                                    <p>
                                        or
                                        <button
                                            type="button"
                                            class="font-medium text-indigo-600 hover:text-indigo-500"
                                            @click.prevent="closeBasket"
                                        >
                                            Continue Shopping
                                            <span aria-hidden="true"> &rarr;</span>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
