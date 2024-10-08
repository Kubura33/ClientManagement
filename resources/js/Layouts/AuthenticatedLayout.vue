<script setup>
import {ref, provide, onMounted, watch} from 'vue';
import {Link, router, usePage} from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar.vue";
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
const page = usePage();
const userMenu = ref(false);

const izabranoTrziste = ref(page.props.trziste)
const header = ref("")
const $toast = useToast()
const otherCompanies = ref([])
const setOtherCompanies = (companies) => {
    otherCompanies.value = companies
}
const setTitle = (title) => {
    header.value = title
}
provide('setTitle', setTitle)
provide('setOtherCompanies', setOtherCompanies)
watch(
    () => page.props.flash,
    (newFlash) => {
        if (newFlash.success) {
            $toast.success(newFlash.success, {
                position: 'top-right',
                duration: 5000,
                dismissible: true
            });
            page.props.flash.success = null; // Optionally reset the flash message
        }

        if (newFlash.error) {
            $toast.error(newFlash.error, {
                position: 'top-right',
                duration: 5000,
                dismissible: true
            });
            page.props.flash.error = null; // Optionally reset the flash message
        }
    },
    { deep: true }  // Deep watch to detect changes within the flash object
);

</script>

<template>
    <div class="h-screen flex bg-[#d6d0b0]">
        <!-- Sidebar -->
        <Sidebar class="mt-0" :izabrano="izabranoTrziste" :trzista="$page.props.trzista" :otherCompanies="otherCompanies" />

        <!-- Main content -->
        <div class="flex-grow">
            <!-- Header -->
            <header class="bg-[#DFD6A7] text-black  fixed w-[calc(100%-300px)] h-[85px] z-10 left-[300px]">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <h2 class="font-roboto text-xl font-semibold leading-tight">
                        B2B Support - Evidencija klijenata - {{ $page.props.auth.user.username }} {{ header }}
                    </h2>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium">Welcome, {{ izabranoTrziste }}</span>
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="{{ $page.props.auth.user.avatarUrl }}" alt="User Avatar">
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="pt-24 px-4 ml-[300px] bg-[#d6d0b0]">
                <slot />
            </main>
        </div>
    </div>
</template>


