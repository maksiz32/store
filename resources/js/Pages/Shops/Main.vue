<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {reactive, ref} from "vue";

const props = defineProps({
    shops: {
        type: Array,
        default: [],
    },
    errorMessage: reactive({
        type: String,
        default: null,
    }),
});
const defaultImg = '/img/default-shop.jpg';
let showErrorRequestMessage = ref(!!props.errorMessage);

const imagePath = (path) => {
    if (path) {
        return path;
    }

    return defaultImg;
}
</script>

<template>
    <Head><title>Main Page</title></Head>
    <AuthenticatedLayout>
        <main class="container mx-auto px-4">
            <div
                class="transition-opacity ease-in-out delay-150 transla flex p-4 my-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800"
                role="alert"
                v-if="showErrorRequestMessage"
            >
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Warning alert!</span> {{ errorMessage }}
                </div>
                <button
                    type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-yellow-50 text-yellow-500 rounded-lg focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-yellow-300 dark:hover:bg-gray-700"
                    data-dismiss-target="#alert-4"
                    aria-label="Close"
                    @click="showErrorRequestMessage = false"
                >
                    <span class="sr-only">Close</span>
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="py-4 bg-white px-3 py-3 grid grid-cols-2 md:grid-cols-3 gap-4">
                <div
                    class="h-auto max-w-full rounded-lg bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
                    v-for="shop in shops"
                >
                    <a :href="route('shop.category', parseInt(shop.store_id, 10))">
                        <img class="rounded-t-lg max-w-65" :src=imagePath(shop.img) alt="Store picture" />
                        <div class="p-5">
                            <h5
                                class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"
                            >
                                {{ shop.name_store }}
                            </h5>
                            <p
                                class="mb-3 font-normal text-gray-700 dark:text-gray-400"
                            >
                                {{ shop.description }}
                            </p>
                            <a :href="route('shop.category', parseInt(shop.store_id, 10))" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-blue">
                                Visit
                                <svg
                                    aria-hidden="true"
                                    class="w-4 h-4 ml-2 -mr-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
