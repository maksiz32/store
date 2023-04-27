<script setup>
import {ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

let dialog = ref(false);
const storeId = usePage().props.auth.user.id;
const emit = defineEmits(['eventSuccess']);
const props = defineProps({
    categories: Array,
});
let selectedCategory = ref(['']);
const form = useForm({
    image: '',
    name: '',
    description: '',
    category_id: null,
    store_id: storeId,
    price: '',
    quantity: '',
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

const submit = () => {
    let _quantity = parseInt(form.quantity, 10);
    form.transform((data) => ({
            ...data,
            category_id: selectedCategory.value,
            image: photo.value,
            quantity: _quantity < 0 ? 0 : _quantity,
        })).post(route('product.add'), {
            onSuccess: () => {
                form.reset();
                selectedCategory.value = [];
                emit('eventSuccess', `Product was created`);
            },
            onError: (error) => {
                console.warn(error);
            },
            onFinish: () => {
                dialog.value = false;
            }
        }
    );
};

watch(selectedCategory, () => {
    if (selectedCategory.value.length) {
        if (selectedCategory.value[0] === '') {
            selectedCategory.value.splice(0, 1);
        }
    } else {
        selectedCategory.value[0] = '';
    }
})
</script>

<template>
    <div justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            width="1024"
        >
            <template v-slot:activator="{ props }">
                <v-btn
                    color="primary"
                    v-bind="props"
                >
                    Add Product
                </v-btn>
            </template>
            <form class="overflow-auto" @submit.prevent="submit">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Add Products</span>
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
                            <div>Which category?</div>
                            <div>
                                <v-select
                                    id="category_id"
                                    class="mt-3"
                                    :items="categories.map(Cat => Cat.name)"
                                    item-value="{{categories.map(Cat => Cat.id)}}"
                                    v-model="selectedCategory"
                                    persistent-hint
                                    single-line
                                    return-object
                                    density="compact"
                                    multiple
                                ></v-select>
                            </div>
                        </v-row>
                        <v-row>
                            <InputLabel for="image" value="Image" />
                            <v-file-input
                                id="image"
                                type="file"
                                class="mt-1 block w-full"
                                @change="uploadPhoto($event.target.files[0])"
                                density="compact"
                            />
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
