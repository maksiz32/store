<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    shop: {
        type: Object,
    }
})

</script>

<template>
    <Head><title>Orders List</title></Head>
    <AuthenticatedLayout>
        <main class="w-[100%]">
            <div class="relative isolate overflow-hidden bg-white py-6">
                <div class="mx-auto w-full px-6 lg:px-8">
                    <h2 class="text-center text-3xl font-bold tracking-tight sm:text-4xl">Orders List</h2>
                    <div class="mx-auto mt-10 lg:mx-0 lg:max-w-none">

                        <table v-if="shop.orders.length" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Count products
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Address
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Phone
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Paid
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="order in shop.orders"
                                class="bg-white border-b dark:bg-gray-900 dark:border-gray-700"
                            >
                                <th
                                    scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                >
                                    {{ order.orders_description.customer.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ order.total_price }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ order.order.length }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ order.orders_description.address }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ order.orders_description.phone }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ order.orders_description.description }}
                                </td>
                                <td class="px-6 py-4">
                                    <svg
                                        v-if="!!order.is_payed"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6 mx-auto"
                                        data-tooltip-target="tooltip-default"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <svg
                                        v-else
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6 mx-auto"
                                        data-tooltip-target="tooltip-default"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </td>
                                <td class="px-6 py-4">
                                    {{ new Date(order.created_at).toLocaleString() }}
                                </td>
                                <td class="px-6 py-4">
                                    <a
                                        :href="route('order.show', order.id)"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-else>
                            <h2>No data.</h2>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
