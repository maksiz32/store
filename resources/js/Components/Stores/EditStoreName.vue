<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    store: {
        type: Object,
        default: {},
    },
    show: {
        type: Boolean,
        default: false,
    }
});
const form = useForm({
    store_id: props.store.store_id,
    name_store: props.store.name_store,
});
const emit = defineEmits(['eventSuccess', 'closeEditFormStoreName']);
let dialog = ref(props.show);

const submit = () => {
    form.put(route('store.update', props.store.store_id), {
            onSuccess: () => {
                emit('eventSuccess', `Store <strong>${form.name_store}</strong> was updated`);
                form.reset();
                emit('closeEditFormStoreName');
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
                        <span class="text-h5">Change Store Name</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <input hidden value="{{ store.store_id }}" name="store_id">
                            <div class="mb-6">
                                <label
                                    for="name_store"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    Store name
                                </label>
                                <input
                                    v-model="form.name_store"
                                    type="text"
                                    id="name_store"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                >
                            </div>

                            <InputError class="mt-2" :message="form.errors.name_store" />
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue-darken-1"
                            variant="text"
                            @click="emit('closeEditFormStoreName')"
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

</style>
