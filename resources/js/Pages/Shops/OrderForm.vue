<script setup>
import {useForm, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    basket: {
        type: Array,
    }
})

const totalPrice = computed(() => {
    let total = 0;
    if (props.basket.length) {
        props.basket.map(product => {
            total += (product.price * product.count)
        });
        total = total !== undefined ? total : 0;
    }

    return total;
});

const form = useForm({
    customer_id: usePage().props.auth.user.id,
    order: props.basket,
    address: '',
    phone: '',
    description: '',
    totalPrice: totalPrice,
});

const submit = () => {
    form.post(route('order.add'), {
        onSuccess: () => {
            form.reset();
        },
        onError: (error) => {
            form.errors = error;
        },
    })
}

const cancellation = () => {
    form.reset();
    history.back();
}
</script>

<template>
    <Head><title>Order confirmation</title></Head>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div class="p-6">
                <form class="overflow-auto" @submit.prevent="submit">
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">Order confirmation</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <InputLabel for="address" value="Address" />

                                    <TextInput
                                        id="address"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.address"
                                        required
                                        autofocus
                                        autocomplete="address"
                                        density="compact"
                                    />

                                    <InputError class="mt-2" :message="form.errors.address" />
                                </v-row>
                                <v-row>
                                    <InputLabel for="phone" value="Phonr number" />

                                    <TextInput
                                        id="phone"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.phone"
                                        autocomplete="phone"
                                        density="compact"
                                    />

                                    <InputError class="mt-2" :message="form.errors.phone" />
                                </v-row>
                                <v-row>
                                    <InputLabel for="description" value="Info" />

                                    <textarea
                                        id="description"
                                        rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Leave a comment..."
                                        v-model="form.description"
                                    ></textarea>

                                    <InputError class="mt-2" :message="form.errors.description" />
                                </v-row>
                                <v-row>
                                    <InputLabel class="mt-3" for="price" value="Total price:" />
                                    <p class="mt-3">&nbsp;<strong>$ {{ totalPrice }}</strong></p>
                                </v-row>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue-darken-1"
                                variant="text"
                                @click="cancellation"
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
