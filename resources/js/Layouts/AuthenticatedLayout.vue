<script setup>
import {ref} from 'vue';
import {Link, router} from "@inertiajs/vue3";

const userMenu = ref(false);

const izabranoTrziste = ref("")
const trzista = [
    {id: 1, name: "Srbija"},
    {id: 2, name: "Bosna"},
    {id: 3, name: "Slovenija"}
];


const header = ref("")
router.on('navigate', () => {
    if (route().current('novi-klijent')) {
        header.value = " - Unos nove firme"
    } else if (route().current('implementirano')) {
        header.value = " - Implementirani"
    } else if (route().current('index')) {
        header.value = ""
    }
})
if (route().current('novi-klijent')) {
    header.value = " - Unos nove firme"
}
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight p-2">
                        B2B Support - Evidencija klijenata - {{ $page.props.auth.user.username }} {{ header }}


                    </h2>


                </div>
            </header>
            <div class="flex">
                <div class="flex-shrink-0 border-t-2 min-h-screen  p-3 bg-white" style="width: 280px;">

                    <ul class="list-unstyled ps-0 ">
                        <li>
                            <div
                                class="space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer"
                                @click="userMenu = !userMenu"
                            >
                                <div class="flex w-full ">
                                    <span class="text-xl text-black">Tržište</span>
                                </div>
                                <span class="text-sm ml-10" id="arrow">
                                    <i :class="!userMenu ? 'bi bi-chevron-down' : 'bi bi-chevron-up'"></i>
                                </span>
                            </div>

                                <div :class="userMenu ? 'flex' : 'hidden'" class="submenu flex flex-col mt-4 gap-4">
                                    <Link
                                        v-for="(trziste, index) in trzista" :key="trziste.id"
                                        class="p-3 text-lg font-semibold cursor-pointer hover:bg-gray-200 transition duration-300 rounded-md no-underline text-current"
                                        @click="izabranoTrziste = trziste.name"
                                        :class="izabranoTrziste === trziste.name ? 'bg-gray-200' : ''"
                                        :href="route('index', {id: trziste.id})"
                                    >
                                        {{ trziste.name }}
                                    </Link>
                                </div>

                        </li>
                        <li>
                            <div
                                class="space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer">
                                <div class="flex w-full">
                                    <Link :href="route('paket.create')" class="text-xl text-black no-underline">Paketi</Link>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div
                                class="space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer">
                                <div class="flex w-full">
                                    <Link :href="route('dodaj-korisnika')" class="text-xl text-black no-underline">Novi korisnik</Link>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <main class="flex-grow-1">
                    <div class="alert alert-success" role="alert" v-if="$page.props.flash.success"
                         @click="$page.props.flash.success=null">
                        {{ $page.props.flash.success }}
                    </div>
                    <div class="alert alert-danger" role="alert" v-if="$page.props.flash.error"
                         @click="$page.props.flash.error=null">
                        {{ $page.props.flash.error }}
                    </div>
                    <slot/>
                </main>
            </div>


        </div>
    </div>
</template>

