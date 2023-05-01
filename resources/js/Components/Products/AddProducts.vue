<script setup>
import {defineProps, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

let dialog = ref(false);
const emit = defineEmits(['eventSuccess']);
const props = defineProps({
    categories: Array,
    store_id: {
        type: Number,
        default: null,
    }
});
const form = useForm({
    image: '',
    name: '',
    description: '',
    category_id: null,
    store_id: props.store_id,
    price: '',
    quantity: '',
    categories: [],
});
let photo = ref(null);

const closeModal = () => {
    dialog.value = false;
    form.reset();
};

const uploadPhoto = (data) => {
    const reader = new FileReader();
    reader.readAsDataURL(data);

    reader.onload = (e) => {
        photo.value = e.target.result;
    };
};

const chooseCategory = (selectCategoriesIds) => {
    let category = [];
    if (selectCategoriesIds.length) {
        selectCategoriesIds.map(selectedCat => {
            category.push(props.categories.find(Cat => Cat.id === parseInt(selectedCat, 10)));
        })
        if (category.length) {
            return category;
        }
    }
    return null;
}

const submit = () => {
    let _quantity = parseInt(form.quantity, 10);
    form.transform((data) => ({
            ...data,
            categories: chooseCategory(form.categories),
            image: photo.value,
            quantity: _quantity < 0 ? 0 : _quantity,
        })).post(route('product.add'), {
            onSuccess: () => {
                form.reset();
                emit('eventSuccess', `Product was created`);
            },
            onError: (error) => {
                form.errors = error;
            },
            onFinish: () => {
                dialog.value = false;
            }
        }
    );
};
</script>

<template>
    <div justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            width="1024"
        >
            <template v-slot:activator="{ props }">
                <button
                    type="button"
                    v-bind="props"
                    class="text-uppercase text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6 pr-1"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    Add Product
                </button>
            </template>
            <form class="overflow-auto" @submit.prevent="submit">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Add Products</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <input hidden value="{{ store_id }}" name="store_id">
                        <v-row>
                            <InputLabel for="name" value="Full name" />

                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                density="compact"
                            />

                            <InputError class="mt-2" :message="form.errors.name" />
                        </v-row>
                        <v-row>
                            <InputLabel for="description" value="Description" />

                            <TextInput
                                id="description"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.description"
                                autocomplete="description"
                                density="compact"
                            />

                            <InputError class="mt-2" :message="form.errors.description" />
                        </v-row>
                        <v-row>
                            <InputLabel for="price" value="Price" />

                            <TextInput
                                id="price"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.price"
                                autocomplete="price"
                                required
                                step="0.01"
                                density="compact"
                            />

                            <InputError class="mt-2" :message="form.errors.price" />
                        </v-row>
                        <v-row>
                            <InputLabel for="quantity" value="Quantity" />

                            <TextInput
                                id="quantity"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.quantity"
                                autocomplete="quantity"
                                required
                                density="compact"
                            />

                            <InputError class="mt-2" :message="form.errors.quantity" />
                        </v-row>
                        <v-row class="flex-column">
                            <div>
                                <label
                                    for="category_id"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    Which category?
                                    &nbsp<small>(You can select multiple options by holding down <kbd class="px-1 py-1 font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-600 dark:text-gray-100 dark:border-gray-500">Ctrl</kbd>)</small>
                                </label>
                                <select
                                    id="category_id"
                                    multiple
                                    v-model="form.categories"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                >
                                    <option
                                        v-for="category in categories"
                                        :value="category.id"
                                    >
                                        {{category.name}}
                                    </option>
                                </select>
                            </div>
                        </v-row>
                        <v-row>
                            <label
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="image"
                            >
                                Upload image file
                            </label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                aria-describedby="file_input_help"
                                id="image"
                                type="file"
                                @change="uploadPhoto($event.target.files[0])"
                            >
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-300"
                                id="file_input_help"
                            >
                                PNG or JPG.
                            </p>

                            <InputError class="mt-2" :message="form.errors.image" />
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue-darken-1"
                        variant="text"
                        @click="closeModal"
                    >
                        Close
                    </v-btn>
                    <v-btn
                        color="blue-darken-1"
                        variant="text"
                        type="submit"
                        :disabled="form.processing"
                    >
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
            </form>
        </v-dialog>
    </div>
</template>
