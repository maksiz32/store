<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link, usePage} from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";
import {computed, onMounted, reactive, ref} from "vue";
import {store} from "@/store/store.js";
import Basket from "@/Components/Baskets/Basket.vue";

const isAuth = ref(!!usePage().props.auth.user);
const isAdmin = ref(usePage().props.auth.user ? usePage().props.auth.user.users_role_id === 20 : false);
const isClient = ref(usePage().props.auth.user ? usePage().props.auth.user.users_role_id === 10 : false);
const isCustomer = ref(usePage().props.auth.user ? usePage().props.auth.user.users_role_id === 1 : false);
let showingNavigationDropdown = ref(false);
let showingBasket = ref(false);

const categories = computed(() => {
    let result = [];
    if (usePage().props.auth.user && usePage().props.auth.user.users_role_id === 10) {
        result = usePage().props.auth.user.categories.filter(Categories => Categories.is_show_nav);
    }

    return result;
});

const closeBasketModal = () => {
    showingBasket.value = false;
}

onMounted(() => store.fetchBasket(usePage().props.auth.user.id));
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Navigation Links -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            v-if="isAuth"
                        >
                            <NavLink :href="route('start.page')" :active="route().current('start.page')">
                                Main Page
                            </NavLink>
                        </div>
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            v-for="category in categories"
                            v-if="isAuth"
                        >
                            <NavLink
                                :href="route('start.page')"
                                :active="route().current(category.name)"
                            >
                                {{ category.name }}
                            </NavLink>
                        </div>
                    </div>


                    <div class="inline-flex align-center">
                        <div class="hidden mr-4 sm:flex sm:items-center sm:ml-6" v-if="!isAdmin && isAuth">
                            <!-- Store -->
                            <div class="ml-3 relative" v-if="!isCustomer">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.store ? $page.props.auth.user.store.name_store : '' }}
                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('store')"> Store Info Page </DropdownLink>
                                        <DropdownLink :href="route('categories')"> Edit Categories </DropdownLink>
                                        <DropdownLink :href="route('products')"> Edit Products </DropdownLink>
                                        <DropdownLink :href="route('orders.list')"> Orders List </DropdownLink>
                                        <DropdownLink
                                            v-if="isAdmin"
                                            :href="route('admin.users')"
                                        >
                                            Users
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isAdmin"
                                            :href="route('admin.stores')"
                                        >
                                            Stores
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="hidden mr-4 sm:flex sm:items-center sm:ml-6" v-if="isAuth">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink
                                            v-if="isAdmin"
                                            :href="route('admin.users')"
                                        >
                                            Users
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="isAdmin"
                                            :href="route('admin.stores')"
                                        >
                                            Stores
                                        </DropdownLink>
                                        <v-divider></v-divider>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="mr-4 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                        <a
                            v-if="isCustomer"
                            href="#"
                            class="text-decoration-none hover:bg-red-100 rounded-full p-2"
                            @click.prevent="showingBasket = true"
                            data-te-toggle="modal"
                            data-te-target="#rightTopModal"
                            data-te-ripple-init
                            data-te-ripple-color="light"
                        >
                            <svg
                                class="text-gray-400 w-6"
                                fill="none"
                                stroke="currentColor"
                                :stroke-width="store.basket.length ? 2.5 : 1.5"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                                :class="store.basket.length ? 'text-red-500': ''"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                            </svg>
                        </a>
                        <span v-if="isCustomer" class="text-red-400">
                            {{ store.getTotalCount() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="sm:hidden"
                v-if="isAuth"
            >
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('start.page')" :active="route().current('start.page')">
                        Dashboard
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
    <Footer />
    <Basket :showingBasket="showingBasket" @closeBasketModal="closeBasketModal"/>
</template>
