<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {computed} from "vue";

let props = defineProps({
    store: {
        type: Object,
    },
})

const categories = computed(() => props.store.categories);
</script>

<template>
    <Head><title>Store: {{store.name_store}}</title></Head>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="p-6">
                <h2 class="mb-6 text-3xl font-bold text-gray-900">{{props.store.name_store}}</h2>
                <h2 class="text-2xl font-bold text-gray-900">Categories</h2>

                <div v-if="categories.length" class="grid grid-cols-2 md:grid-cols-3 gap-4 my-2 px-2">
                    <div
                        v-for="category in categories"
                        class="w-full max-w-sm bg-white rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
                    >
                        <div class="group relative">
                            <div class="relative h-80 w-full overflow-hidden rounded-t bg-white sm:aspect-h-1 sm:aspect-w-2 lg:aspect-h-1 lg:aspect-w-1 group-hover:opacity-75 sm:h-64">
                                <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg" alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug." class="h-full w-full object-cover object-center">
                            </div>
                            <div class="p-4">
                                <h3 class="mt-2 text-sm text-gray-500">
                                    <a :href="route('shop.show', {client_id: store.store_id, category_id: parseInt(category.id, 10)})">
                                        <span class="absolute inset-0"></span>
                                        {{ category.name }}
                                    </a>
                                </h3>
                                <p
                                    v-if="category.products.length"
                                    class="text-base font-semibold text-gray-900"
                                >
                                    Count of products: {{ category.products.length }}
                                </p>
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
