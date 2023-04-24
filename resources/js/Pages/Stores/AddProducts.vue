<script setup>
import {computed, reactive, ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from "axios";

let dialog = ref(false);
const storeId = usePage().props.auth.user.id;
const emit = defineEmits(['addProductSuccess']);
const props = defineProps({
    categories: Array,
});
const selectedCategory = ref([]);
const form = useForm({
    image: null,
    name: '',
    description: '',
    category_id: null,
    store_id: storeId,
    price: '',
    quantity: '',
});

const closeModal = () => {
    dialog.value = false;
    form.reset();
};

const submit = () => {
    if (form.image) {
        const fileReader = new FileReader();
        fileReader.onload = () => {
            form.image = fileReader.result;
        }
        fileReader.readAsDataURL(form.image);
    }
    form.post(route('add.product'), {
        onSuccess: () => {
            emit('addProductSuccess', `Product was created`);
        },
        onFinish: () => {
            form.reset();
            dialog.value = false;
        }
    });
};

const chooseCategory = () => {
    form.category_id = props.categories.filter(Cat => {
        if (Cat.name === selectedCategory.value) {
            return Cat.id;
        }
    })
}
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
                            />

                            <InputError class="mt-2" :message="form.errors.quantity" />
                        </v-row>
                        <v-row class="flex-column">
                            <div>Which category?</div>
                            <div>
                                <v-select
                                    class="mt-3"
                                    :items="categories.map(Cat => Cat.name)"
                                    v-model="selectedCategory"
                                    @change="chooseCategory"
                                    persistent-hint
                                    single-line
                                ></v-select>
                            </div>
                        </v-row>
                        <v-row>
                            <InputLabel for="image" value="Image" />

                            <v-file-input
                                id="image"
                                type="file"
                                class="mt-1 block w-full"
                                @input="form.image = $event.target.files[0]"
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
                    >
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
            </form>
        </v-dialog>
    </div>
</template>
