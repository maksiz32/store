<script setup>
import {ref, watch} from "vue";
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

const chooseCats = () => {
    if (props.product.categories.length) {
        return props.product.categories.map(Cat => {
            const category = props.categories.find(cats => cats.id === Cat.id);
            return category.name;
        });
    }
}
let selectedCategory = ref(['']);

const chooseCategory = () => {
    if (selectedCategory.value.length) {
        const category = props.categories.find(Cat => Cat.name === selectedCategory.value);
        if (category) {
            return category.id;
        }
    }
    return null;
}

const submit = () => {
    let _quantity = parseInt(form.quantity, 10);
    form.transform((data) => ({
        ...data,
        category_id: chooseCategory(),
        description: data.description || '',
        image: photo.value,
        quantity: _quantity < 0 ? 0 : _quantity,
    })).put(route('product.update', props.product.id), {
            onSuccess: () => {
                form.reset();
                selectedCategory.value = [];
                emit('eventSuccess', `Product was updated`);
                dialog.value = false;
            },
            onError: (error) => {
                console.warn(error);
            },
        }
    );
}
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
                                <div>Which category?</div>
                                <div>
                                    <v-select
                                        id="category_id"
                                        class="mt-3"
                                        :items="categories.map(Cat => Cat.name)"
                                        item-value="{{categories.map(Cat => Cat.id)}}"
                                        v-model="selectedCategory"
                                        @change="chooseCategory"
                                        persistent-hint
                                        single-line
                                        return-object
                                        density="compact"
                                        multiple
                                    ></v-select>
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
                                    <label>Choose new image if you want to change</label>
                                    <InputLabel for="image" />
                                    <v-file-input
                                        id="image"
                                        type="file"
                                        class="mt-1 block w-full"
                                        @change="uploadPhoto($event.target.files[0])"
                                        density="compact"
                                    />
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
