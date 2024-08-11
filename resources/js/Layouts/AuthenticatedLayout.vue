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
    <div>
        <div class="min-h-screen bg-blue-100">
            <!-- Page Heading -->
            <header class="bg-white shadow fixed w-full h-[100px] z-1">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight p-2">
                        B2B Support - Evidencija klijenata - {{ $page.props.auth.user.username }} {{ header }}


                    </h2>


                </div>
            </header>
            <div class="flex">
                <Sidebar class="mt-[100px]"

                         :izabrano="izabranoTrziste"
                         :trzista="$page.props.trzista"
                         :otherCompanies="otherCompanies"
                />
<!--                <div class="flex-shrink-0 border-t-2 min-h-screen  p-3 bg-white" style="width: 280px;">-->

<!--                    <ul class="list-unstyled ps-0 ">-->
<!--                        <li>-->
<!--                            <div-->
<!--                                class="space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer"-->
<!--                                @click="userMenu = !userMenu"-->
<!--                            >-->
<!--                                <div class="flex w-full ">-->
<!--                                    <span class="text-xl text-black">Tržište</span>-->
<!--                                </div>-->
<!--                                <span class="text-sm ml-10" id="arrow">-->
<!--                                    <i :class="!userMenu ? 'bi bi-chevron-down' : 'bi bi-chevron-up'"></i>-->
<!--                                </span>-->
<!--                            </div>-->

<!--                                <div :class="userMenu ? 'flex' : 'hidden'" class="submenu flex flex-col mt-4 gap-4">-->
<!--                                    <Link-->
<!--                                        v-for="(trziste, index) in trzista" :key="trziste.id"-->
<!--                                        class="p-3 text-lg font-semibold cursor-pointer hover:bg-gray-200 transition duration-300 rounded-md no-underline text-current"-->
<!--                                        @click="izabranoTrziste = trziste.name"-->
<!--                                        :class="izabranoTrziste === trziste.name ? 'bg-gray-200' : ''"-->
<!--                                        :href="route('index', {id: trziste.id})"-->
<!--                                    >-->
<!--                                        {{ trziste.name }}-->
<!--                                    </Link>-->
<!--                                </div>-->

<!--                        </li>-->
<!--                        <li>-->
<!--                            <div-->
<!--                                class="space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer">-->
<!--                                <div class="flex w-full">-->
<!--                                    <Link :href="route('paket.create')" class="text-xl text-black no-underline">Paketi</Link>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <div-->
<!--                                class="space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer">-->
<!--                                <div class="flex w-full">-->
<!--                                    <Link :href="route('dodaj-korisnika')" class="text-xl text-black no-underline">Novi korisnik</Link>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
                <main class="flex-grow-1 p-4 mt-[100px]">
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

