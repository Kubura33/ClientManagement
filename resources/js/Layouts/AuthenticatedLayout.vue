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
    <div class="h-screen">
        <div class="min-h-screen bg-[#E5E8EF] " >
            <!-- Page Heading -->
            <header class="bg-indigo-600 text-white shadow-lg fixed w-full h-[85px] z-10">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <!-- Left section: Title -->
                    <h2 class="font-roboto text-xl font-semibold leading-tight">
                        B2B Support - Evidencija klijenata - {{ $page.props.auth.user.username }} {{ header }}
                    </h2>

                    <!-- Right section: User Info -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium">Welcome, {{ izabranoTrziste }}</span>
                        <img class="w-8 h-8 rounded-full border-2 border-white" src="{{ $page.props.auth.user.avatarUrl }}" alt="User Avatar">
                    </div>
                </div>
            </header>

            <div class="flex ">
                <Sidebar class="mt-[85px] "

                         :izabrano="izabranoTrziste"
                         :trzista="$page.props.trzista"
                         :otherCompanies="otherCompanies"
                />
                <main class="flex-grow-1 pt-10 p-4 mt-[85px] transform origin-top-left">
                    <slot />
                </main>
            </div>


        </div>
    </div>
</template>

