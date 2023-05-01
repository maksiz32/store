<script setup>
import {reactive, ref} from "vue";
import {Head, useForm, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddCategories from "@/Components/Categories/AddCategories.vue";
import Notification from "@/Components/Notification.vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import EditCategory from "@/Components/Categories/EditCategory.vue";

const props = defineProps({
    categories: Object,
})
const storeId = usePage().props.auth.user.id;
const modalEvents = ref(null);
const categoryId = ref(null);
const confirmingCategoryDeletion = ref(null);
let openEditForm = ref(false);
let message = ref('');
let currentCategory = reactive({
    id: null,
    name: '',
    description: '',
    is_show_nav: null,
});

const confirmCategoryDeletion = (id) => {
    categoryId.value = id;
    confirmingCategoryDeletion.value = true;
};

const deleteCategory = () => {
    const category = props.categories.find(Category => Category.id === categoryId.value);
    useForm({}).delete(route('category.delete', categoryId.value), {
        onSuccess: () => {
            eventSuccess(`Category <b>${category.name}</b> was deleted`);
            closeModalCategoryDelete();
            closeModalCategoryDelete();
        },
    });
};

const closeModalCategoryDelete = () => {
    categoryId.value = null;
    confirmingCategoryDeletion.value = false;
};

const openingEditForm = (category) => {
    currentCategory = category;
    openEditForm.value = true;
}
const closeEditForm = () => {
    currentCategory = {};
    openEditForm.value = false;
};

const eventSuccess = (mes) => {
    message.value = mes;
    if (!!mes) {
        modalEvents.value.openNotification();
    }
}
</script>

<template>
    <Head><title>Edit Categories</title></Head>

    <AuthenticatedLayout>
        <section class="flex justify-space-around">
            <AddCategories :store_id="storeId" @eventSuccess="eventSuccess" />
        </section>
        <v-table
            hover
            fixed-header
        >
            <thead>
            <tr>
                <th class="text-left">
                    Name
                </th>
                <th class="text-left">
                    Description
                </th>
                <th class="text-left">
                    Show in Navbar
                </th>
                <th class="text-center">
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="category in categories"
                :key="category.id"
            >
                <td>{{ category.name }}</td>
                <td>{{ category.description }}</td>
                <td>{{ category.is_show_nav ? '&#9745 Yes' : 'No' }}</td>
                <td class="text-center">
                    <v-btn
                        variant="outlined"
                        size="x-small"
                        small
                        @click="openingEditForm(category)"
                    >
                        Edit Category
                    </v-btn>
                    <v-btn
                        variant="outlined"
                        size="x-small"
                        @click="confirmCategoryDeletion(category.id)"
                    >
                        Delete Category
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-table>

        <Modal :show="confirmingCategoryDeletion" @close="closeModalCategoryDelete">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this product's category?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    This action is irreversible.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalCategoryDelete"> Cancel </SecondaryButton>
                    <DangerButton
                        class="ml-3"
                        @click="deleteCategory"
                    >
                        Delete Category
                    </DangerButton>
                </div>
            </div>
        </Modal>
        <Modal :show="openEditForm">
            <EditCategory
                :show="openEditForm"
                :category="currentCategory"
                @closeEditForm="closeEditForm"
                @eventSuccess="eventSuccess"
            />
        </Modal>
        <Notification :message="message" ref="modalEvents" />
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
