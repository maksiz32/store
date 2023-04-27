<script setup>
import {ref} from "vue";

const props = defineProps({
    message: {
        type: String,
    }
})
const visible = ref(false);
const openNotification = () => visible.value = true
const closeNotification = () => visible.value = false

defineExpose({ openNotification, closeNotification })
</script>

<template>
    <v-snackbar
        v-model="visible"
        color="grey-lighten-1"
    >
        <span v-html="message"></span>
        <template v-slot:actions>
            <v-btn
                class="font-weight-black"
                color="red"
                variant="text"
                @click="visible = false"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<style scoped>

</style>
