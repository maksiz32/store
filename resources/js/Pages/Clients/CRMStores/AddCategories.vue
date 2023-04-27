<script setup>
import {ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

let dialog = ref(false);
const storeId = usePage().props.auth.user.id;
const emit = defineEmits(['eventSuccess']);
const form = useForm({
    name: '',
    description: '',
    store_id: storeId,
    is_show_nav: true,
});

const closeModal = () => {
    dialog.value = false;
    form.reset();
};

const submit = () => {
    form.post(route('categories.add'), {
        onSuccess: () => {
            emit('eventSuccess', `Category was created`);
            form.reset('name', 'description');
            dialog.value = false;
        },
        onError: (error) => {
            console.warn(error);
        },
    });
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
                <v-btn
                    color="primary"
                    v-bind="props"
                >
                    Add Product's Categories
                </v-btn>
            </template>
            <form>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Add Categories</span>
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
                        <v-row class="flex-column">
                            <div>
                                <v-switch
                                    label="Show in Navbar?"
                                    inset
                                    color="primary"
                                    v-model="form.is_show_nav"
                                ></v-switch>
                            </div>
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
                    <v-spacer></v-spacer>
                    <v-btn
                        @click.prevent="submit"
                        color="blue-darken-1"
                        variant="text"
                    >
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
            </form>
        </v-dialog>
    </div>
</template>
