<script setup>
import {ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: {
        type: Object,
        default: {},
    },
    show: {
        type: Boolean,
        default: false,
    }
});
const form = useForm({
    id: props.user.id,
    name: props.user.name,
    email: props.user.email,
});
const emit = defineEmits(['eventSuccess', 'closeEditForm']);
let dialog = ref(props.show);

const submit = () => {
    // Save changes
    form.reset();
    emit('eventSuccess', 'User updated');
    dialog.value = false;
}
</script>
<template>
    <teleport to="body">
        <div v-if="dialog" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
            <form class="overflow-auto" @submit.prevent="submit">
                <v-card>
                    <v-card-title>
                        <span class="text-h5">Edit User</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <InputLabel for="name" value="Full name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="name"
                                    density="compact"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />
                            </v-row>
                            <v-row>
                                <InputLabel for="email" value="Email" />

                                <TextInput
                                    id="email"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    autocomplete="email"
                                    density="compact"
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </v-row>
                            <v-row>
                                {{props.user.users_roles.name}}
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

</style>
