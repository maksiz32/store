<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>

<template>
    <Head><title>Orders List</title></Head>
    <AuthenticatedLayout>
        <main class="w-100%">
            <div class="relative isolate overflow-hidden bg-white py-6">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <h2 class="text-center text-3xl font-bold tracking-tight sm:text-4xl">Orders List</h2>
                    <div class="mx-auto mt-10 max-w-2xl lg:mx-0 lg:max-w-none">

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Price
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
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    First A
                                </th>
                                <td class="px-6 py-4">
                                    5000
                                </td>
                                <td class="px-6 py-4">
                                    17-12-2023 12:56
                                </td>
                                <td class="px-6 py-4">
                                    <a
                                        :href="route('order', 1)"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                            <tr class="border-b bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Second AB
                                </th>
                                <td class="px-6 py-4">
                                    6000
                                </td>
                                <td class="px-6 py-4">
                                    17-12-2023 12:56
                                </td>
                                <td class="px-6 py-4">
                                    <a
                                        :href="route('order', 1)"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Magic Mouse
                                </th>
                                <td class="px-6 py-4">
                                    254.22
                                </td>
                                <td class="px-6 py-4">
                                    17-12-2023 12:56
                                </td>
                                <td class="px-6 py-4">
                                    <a
                                        :href="route('order', 1)"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
