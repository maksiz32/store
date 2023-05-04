<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import {reactive, ref} from "vue";
import Register from "@/Pages/Auth/Register.vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Notification from "@/Components/Notification.vue";
import EditUser from "@/Components/Users/EditUser.vue";

const props = defineProps(reactive({
    users: {
        type: Array,
    },
}));
const currentAdminId = usePage().props.auth.user.id;
let openAddForm = ref(false);
let openEditForm = ref(false);
let openAddFormClients = ref(false);
let confirmingUserDeletion = ref(false);
let userId = ref(null);
let modalEvents = ref(null);
let message = ref('');
let currentUser = ref({});

const confirmUserDeletion = (id) => {
    userId.value = id;
    confirmingUserDeletion.value = true;
};

const deleteUser = () => {
    const user = props.users.find(Category => Category.id === userId.value);
    useForm({}).delete(route('user.delete', userId.value), {
        onSuccess: () => {
            eventSuccess(`User <b>${user.name}</b> was deleted`);
            closeModalUserDelete();
        },
    });
};

const closeModalUserDelete = () => {
    userId.value = null;
    confirmingUserDeletion.value = false;
};

const openingEditForm = (user) => {
    currentUser = user;
    openEditForm.value = true;
}
const closeEditForm = () => {
    openEditForm.value = false;
    currentUser = {};
};

const eventSuccess = (mes) => {
    message.value = mes;
    if (!!mes) {
        modalEvents.value.openNotification();
    }
    closeEditForm();
}
</script>

<template>
    <Head><title>All users</title></Head>
    <AuthenticatedLayout>
    <div class="store-users__table">
        <v-btn
            color="success"
            class="mb-2"
            @click="openAddForm = true"
        >
            Add Admin
        </v-btn>
        <Modal :show="openAddForm" :closeable="true" @close="openAddForm = false">
            <Register
                :closable="true"
                @closeRegister="openAddForm = false"
                :isMustAdmin="true"
                :isAdminMode="true"
            />
        </Modal>
        <v-btn
            color="success"
            class="mb-2"
            @click="openAddFormClients = true"
        >
            Add Client
        </v-btn>
        <Modal :show="openAddFormClients" :closeable="true" @close="openAddFormClients = false">
            <Register
                :closable="true"
                @closeRegister="openAddFormClients = false"
                :isMustAdmin="false"
                :isAdminMode="true"
            />
        </Modal>
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
                    <v-btn
                        variant="outlined"
                        size="x-small"
                        @click="openingEditForm(user)"
                    >
                        Manage User
                    </v-btn>
                    <v-btn
                        v-if="currentAdminId !== user.id"
                        variant="outlined"
                        size="x-small"
                        @click="confirmUserDeletion(user.id)"
                    >
                        Delete User
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-table>
    </div>

        <Modal :show="confirmingUserDeletion" @close="closeModalUserDelete">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this user?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    This action is irreversible.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalUserDelete"> Cancel </SecondaryButton>
                    <DangerButton
                        class="ml-3"
                        @click="deleteUser"
                    >
                        Yes, delete User
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <Modal :show="openEditForm">
            <EditUser
                :show="openEditForm"
                :user="currentUser"
                @closeEditForm="closeEditForm"
                @eventSuccess="eventSuccess"
            />
        </Modal>
        <Notification :message="message" ref="modalEvents" />
    </AuthenticatedLayout>
</template>

<style scoped>
.store-users__table {
    padding: 20px;
}
</style>
