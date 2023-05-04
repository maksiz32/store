<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Notification from "@/Components/Notification.vue";
import {ref} from "vue";

const props = defineProps({
    order: {
        type: Object,
    },
});
const modalEvents = ref(null);
let message = ref('');

const makeOrderPaid = (order) => {
    useForm({}).patch(route('order.paid', order), {
        onSuccess: () => {
            eventSuccess('Order paid.');
        },
    });
}

const eventSuccess = (mes) => {
    message.value = mes;
    if (!!mes) {
        modalEvents.value.openNotification();
    }
}
</script>

<template>
    <Head><title>Order</title></Head>
    <AuthenticatedLayout>
        <div class="bg-white">
            <div v-if="order" class="p-6">
                <h2 class="mb-4 text-3xl font-bold text-gray-900">Order #{{ order.id }}</h2>
                <div class="mb-5">
                    Total price: {{ order.total_price }}<br />
                    Bonus: {{ order.bonus }}<br />
                    Paid?: {{ !!order.is_payed ? '&#10004;' : '&#10008;' }}
                </div>
                <h3 class="mb-4 text-xl font-bold text-gray-600">Order's description:</h3>
                <div class="mb-5">
                    Customer: {{ order.orders_description.customer.name }}<br />
                    E-mail: {{ order.orders_description.customer.email }}<br />
                    Phone: {{ order.orders_description.phone }}<br />
                    Address: {{ order.orders_description.address }}<br />
                    Order description: {{ order.orders_description.description }}
                </div>
                <h3 class="mb-4 text-xl font-bold text-gray-600">Products in order:</h3>
                <div v-for="ord in order.order" class="mb-2 border-b-2 border-gray-400">
                    Product: {{ ord.name }}<br />
                    Product: {{ ord.price }}<br />
                    Product: {{ ord.count }}
                </div>
                <button
                    v-if="!order.is_payed"
                    type="button"
                    @click.prevent="makeOrderPaid(order.id)"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                >
                    Make it paid
                </button>
                <button
                    v-else
                    type="button"
                    @click.prevent="makeOrderPaid(order.id)"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                >
                    Mark as unpaid
                </button>
            </div>
            <div v-else class="p-6">
                No data.
            </div>
        </div>
        <Notification :message="message" ref="modalEvents" />
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
