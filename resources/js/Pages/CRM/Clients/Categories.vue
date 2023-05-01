<script setup>
import {onMounted, onUpdated, reactive, ref} from "vue";
import {Head, useForm, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AddCategory from "@/Components/Categories/AddCategory.vue";
import Notification from "@/Components/Notification.vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import EditCategory from "@/Components/Categories/EditCategory.vue";
import {initFlowbite} from "flowbite";

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

const changeVisibleInNav = (categoryId) => {
    const category = props.categories.find(Category => Category.id === categoryId.value);
    useForm({}).patch(route('category.activity', categoryId), {
        onSuccess: () => {
            eventSuccess(`Category <strong>"${category.name_store}"</strong> was updated`);
        },
    });
}

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
onMounted(() => {
    initFlowbite();
})
onUpdated(() => {
    initFlowbite();
})
</script>

<template>
    <Head><title>Edit Categories</title></Head>

    <AuthenticatedLayout>
        <main class="container mx-2 my-1 mx-auto">
            <section class="">
                <AddCategory :store_id="storeId" @eventSuccess="eventSuccess" />
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
                    <td>
                        <span
                            class="cursor-pointer"
                            data-tooltip-target="tooltip-change-activity"
                            @click="changeVisibleInNav(category.id)"
                        >
                            {{ category.is_show_nav ? '&#10004;' : '&#10008;' }}
                        </span>
                    </td>
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
        </main>

        <div
            id="tooltip-change-activity"
            role="tooltip"
            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
        >
            Click to change status activity.
            <div data-popper-arrow></div>
        </div>
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
