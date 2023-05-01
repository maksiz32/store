<script setup>
import {defineProps, ref} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

let dialog = ref(false);
const props = defineProps({
    store_id: {
        type: Number,
        default: null,
    }
});
const emit = defineEmits(['eventSuccess']);
const form = useForm({
    name: '',
    description: '',
    store_id: props.store_id,
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
            form.errors = error;
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
                    Add Category
                </button>
            </template>
            <form>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Add Categories</span>
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
                            <div class="mt-3">
                                <label class="relative inline-flex items-center mr-5 cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer" v-model="form.is_show_nav">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                    >
                                        Show in Navbar?
                                    </span>
                                </label>
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
