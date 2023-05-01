<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm, usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    category: {
        type: Object,
        default: {},
    },
    show: {
        type: Boolean,
        default: false,
    }
});
const form = useForm({
    id: props.category.id,
    name: props.category.name,
    description: props.category.description || '',
    is_show_nav: props.category.is_show_nav,
    store_id: props.category.store_id,
});
const storeId = usePage().props.auth.user.id;
const emit = defineEmits(['eventSuccess', 'closeEditForm']);
let dialog = ref(props.show);

const submit = () => {
    form.put(route('category.update', form.id), {
        onSuccess: () => {
            emit('eventSuccess', `Category was updated`);
            emit('closeEditForm');
            form.reset();
            dialog.value = false;
        },
        onError: (error) => {
            form.errors = error;
        },
    });
};
</script>

<template>
    <teleport to="body">
        <div v-if="dialog" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
            <form>
                <v-card>
                    <v-card-title>
                        <span class="text-h5">Edit Category</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <input hidden value="{{ category.store_id }}" name="store_id">
                            <input hidden value="{{ category.id }}" name="id">
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
                            @click="emit('closeEditForm')"
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
        </div>
    </teleport>
</template>

<style scoped>

</style>
