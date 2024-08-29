<script setup>
import {ref, provide} from 'vue';
import {Link, router, usePage} from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar.vue";

const page = usePage();
const userMenu = ref(false);

const izabranoTrziste = ref(page.props.trziste)
const header = ref("")

const otherCompanies = ref([])
const setOtherCompanies = (companies) => {
    otherCompanies.value = companies
}
const setTitle = (title) => {
    header.value = title
}
provide('setTitle', setTitle)
provide('setOtherCompanies', setOtherCompanies)


</script>

<template>
    <div class="h-screen">
        <div class="min-h-screen bg-blue-100 " >
            <!-- Page Heading -->
            <header class="bg-white shadow fixed w-full h-[100px] z-1 text-[80%] " >
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 scale-90 transform origin-top-left">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight p-2">
                        B2B Support - Evidencija klijenata - {{ $page.props.auth.user.username }} {{ header }}


                    </h2>


                </div>
            </header>
            <div class="flex ">
                <Sidebar class="mt-[100px] "

                         :izabrano="izabranoTrziste"
                         :trzista="$page.props.trzista"
                         :otherCompanies="otherCompanies"
                />
                <main class="flex-grow-1 p-4 mt-[100px] scale-90 transform origin-top-left">
                    <div class="alert alert-success" role="alert" v-if="$page.props.flash.success"
                         @click="$page.props.flash.success=null">
                        {{ $page.props.flash.success }}
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="$page.props.flash.error"
                         @click="$page.props.flash.error=null">
                        {{ $page.props.flash.error }}
                    </div>
                    <slot />
                </main>
            </div>


        </div>
    </div>
</template>

