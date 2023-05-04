<script setup>
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>

<template>
    <Head><title>Order</title></Head>
    <AuthenticatedLayout>
    <div class="bg-white py-6">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            Order from customer ...:
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
