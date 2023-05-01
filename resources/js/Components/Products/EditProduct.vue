<script setup>
import {ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    product: {
        type: Object,
        default: {},
    },
    categories: {
        type: Object,
        default: {},
    },
    show: {
        type: Boolean,
        default: false,
    }
});
let photo = ref(props.product.image);
const form = useForm({
    id: props.product.id,
    name: props.product.name,
    description: props.product.description || '',
    price: props.product.price.toString(),
    quantity: props.product.quantity.toString(),
    store_id: props.product.store_id,
    image: '',
    categories: props.product.categories.map(Cat => Cat.id),
});
const storeId = usePage().props.auth.user.id;
const emit = defineEmits(['eventSuccess', 'closeEditForm']);
let dialog = ref(props.show);

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
        description: data.description || '',
        image: photo.value,
        quantity: _quantity < 0 ? 0 : _quantity,
    })).put(route('product.update', props.product.id), {
            onSuccess: () => {
                form.reset();
                emit('eventSuccess', `Product was updated`);
                dialog.value = false;
            },
            onError: (error) => {
                form.errors = error;
            },
        }
    );
}
</script>
<template>
    <teleport to="body">
        <div v-if="dialog" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
            <form class="overflow-auto" @submit.prevent="submit">
                <v-card>
                    <v-card-title>
                        <span class="text-h5">Edit Product</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <input hidden value="{{ storeId }}" name="store_id">
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
                                <v-col cols="2">
                                    <v-img
                                        :src="photo"
                                        class="store-crm-edit-product__photo"
                                    />
                                </v-col>
                                <v-col cols="10">
                                    <label
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                        for="image"
                                    >
                                        Choose new image if you want to change
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
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue-darken-1"
                            variant="text"
                            @click="emit('closeEditForm')"
                        >
                            Close
                        </v-btn>
                        <v-btn
                            @click.prevent="submit"
                            color="blue-darken-1"
                            variant="text"
                            :disabled="form.processing"
                        >
                            Save
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </form>
        </div>
    </teleport>

</template>
<style scoped>
.store-crm-edit-product__photo {
    max-height: 80px;
}
</style>
