<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    shops: {
        type: Array,
        default: [],
    },
});
const defaultImg = '/img/default-shop.jpg';

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
            <div class="bg-white px-3 py-3 grid grid-cols-2 md:grid-cols-3 gap-4">
                <div
                    class="h-auto max-w-full rounded-lg bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
                    v-for="shop in shops"
                >
                    <a :href="route()">
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
                            <a href="#1" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Visit store
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
