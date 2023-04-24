<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {reactive} from "vue";

const props = defineProps({
    users: reactive({
        type: Array,
    }),
});
</script>

<template>
    <Head><title>All users</title></Head>
    <AuthenticatedLayout>
    <div class="store-users__table">
        <v-btn
            color="success"
            class="mb-2"
            :href="route('admin.create')"
        >
            Add Admin
        </v-btn>
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
                    Full Name
                </th>
                <th class="text-left">
                    Email
                </th>
                <th class="text-left">
                    Role
                </th>
                <th class="text-left">
                    Store name
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
                v-for="user in users"
                :key="user.id"
            >
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.users_roles.name }}</td>
                <td>{{ user.store ? user.store.name_store : '' }}</td>
                <td>{{ new Date(user.created_at).toLocaleString() }}</td>
                <td justify="center">
                    <v-btn variant="outlined" size="x-small" :href="route('dashboard')">
                        Manage Client
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
.store-users__table {
    padding: 20px;
}
</style>
