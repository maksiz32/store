<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>

<template>
    <Head><title>Order successfully</title></Head>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="mx-auto p-6">
                <h2 class="mb-6 text-3xl font-bold text-gray-900">Congratulation! Your order was successfully.</h2>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
