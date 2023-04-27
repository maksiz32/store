<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    stores: {
        type: Array,
    },
});
</script>

<template>
    <Head><title>All stores</title></Head>
    <AuthenticatedLayout>
        <div class="store-stores__table">
            <v-table
                hover
                fixed-header
            >
                <thead>
                <tr>
                    <th class="text-left">
                        ID
                    </th>
                    <th class="text-left">
                        Store Name
                    </th>
                    <th class="text-left">
                        Description
                    </th>
                    <th class="text-left">
                        Client
                    </th>
                    <th class="text-left">
                        Products count
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
                    v-for="store in stores"
                    :key="store.id"
                >
                    <td>{{ store.store_id }}</td>
                    <td>{{ store.name_store }}</td>
                    <td>{{ store.description }}</td>
                    <td @onclick="" class="stores-table-cell__target">
                        {{ store.client ? store.client.name : '' }}
                    </td>
                    <td>{{ store.products ? store.products.length : 0 }}</td>
                    <td>{{ new Date(store.created_at).toLocaleString() }}</td>
                    <td justify="center">
                        <v-btn variant="outlined" size="x-small" :href="route('dashboard')">
                            View Store Info
                        </v-btn>
                        <v-btn variant="outlined" size="x-small" :href="route('dashboard')">
                            Delete Store
                        </v-btn>
                    </td>
                </tr>
                </tbody>
            </v-table>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.store-stores__table {
    padding: 20px;
}

.stores-table-cell__target:hover {
    cursor: pointer;
    border: 1px solid lightgreen;
}
</style>
