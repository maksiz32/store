<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    isAdminMode: {
        type: Boolean,
        default: false,
    },
    closable: {
        type: Boolean,
        default: false,
    },
    isMustAdmin: {
        type: Boolean,
        default: false,
    }
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    users_role_id: 1,
    store_name: '',
});

const emit = defineEmits(['closeRegister']);

const submit = () => {
    form.transform((data) => ({
        ...data,
        users_role_id: props.isAdminMode ? props.isMustAdmin ? 20 : 10 : 1,
    })).post(route('register'), {
        onError: (error) => {
            form.errors = error;
        },
        onFinish: () => {
            form.reset();
            if (props.closable) {
                emit('closeRegister');
            }
        }
    });
};
</script>

<template>
    <div class="store-form-register">
        <Head>
            <title v-if="props.isAdminMode">Create users</title>
            <title v-else>Register</title>
        </Head>

        <form @submit.prevent="submit" class="store-form-register-user__form">
            <div>
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
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4" v-if="!isMustAdmin && isAdminMode">
                <InputLabel for="store_name" value="Store name" />

                <TextInput
                    id="store_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.store_name"
                    autocomplete="store_name"
                />

                <InputError class="mt-2" :message="form.errors.store_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Password confirm" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="!props.isAdminMode"
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <SecondaryButton
                    v-if="props.closable"
                    @click="emit('closeRegister')"
                    class="ml-6"
                >
                    Cancel
                </SecondaryButton>
                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<style scoped>
.store-form-register {
    padding: 40px 0;
}
.store-form-register-user__form {
    margin: 0 auto;
    width: 400px;
}
</style>
