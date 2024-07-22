<script setup>
import {ref} from 'vue';
import {Link} from "@inertiajs/vue3";

const userMenu = ref(false);
const procesMenu = ref(false);
const izabranoTrziste = ref("")
const trzista = ["Srbija", "Bosna", "Slovenija"]
const filter = ref('')
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight p-2">
                        B2B Support - Evidencija klijenata - {{ $page.props.auth.user.username }} -
                        <slot name="header"/>

                    </h2>


                </div>
            </header>
            <div class="flex">
                <div class="flex-shrink-0 border-t-2 min-h-screen  p-3 bg-white" style="width: 280px;">

                    <ul class="list-unstyled ps-0 ">
                        <li>
                            <div class=" space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer"
                                 @click="userMenu = !userMenu">
                                <div class="flex w-full ">
                                    <span class="text-xl text-black ">Trziste</span>

                                </div>
                                <span class="text-sm ml-10" id="arrow">
                                    <i :class="!userMenu ? 'bi bi-chevron-down' : 'bi bi-chevron-up'"></i>
                                </span>

                            </div>
                            <div
                                :class="userMenu ? 'flex' : 'hidden'"

                                class="submenu flex flex-col mt-4 gap-4">
                                <span
                                    v-for="(trziste, index) in trzista" :key="index"
                                    class="p-3 text-lg font-semibold cursor-pointer"
                                    @click="izabranoTrziste=trziste"
                                    :class="izabranoTrziste==trziste ? 'bg-gray-200' : 'bg-none'"
                                >

                                    {{ trziste }}
                                </span>

                            </div>
                        </li>
                        <li v-if="izabranoTrziste">
                            <div class=" space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer"
                            >
                                <div class="flex w-full ">
                                    <Link :href="route('novi-klijent')" class="text-xl text-black ">Novi klijent</Link>
                                </div>

                            </div>
                        </li>
                        <li v-if="izabranoTrziste" class="mt-2">
                            <div class=" space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer"
                                 @click="procesMenu = !procesMenu">
                                <div class="flex w-full ">
                                    <span class="text-xl text-black ">U procesu</span>

                                </div>
                                <span class="text-sm ml-10" id="arrow">
                                    <i :class="!procesMenu ? 'bi bi-chevron-down' : 'bi bi-chevron-up'"></i>
                                </span>

                            </div>
                            <div
                                :class="procesMenu ? 'flex' : 'hidden'"

                                class="submenu flex flex-col mt-4 gap-4">

                                <span

                                    class="p-3 text-lg font-semibold cursor-pointer"
                                    @click="filter='U toku'"
                                    :class="filter=='U toku' ? 'bg-gray-200' : 'bg-none'"
                                >

                                    U toku
                                </span>
                                <span

                                    class="p-3 text-lg font-semibold cursor-pointer"
                                    @click="filter='Ceka se neka funkcionalnost'"
                                    :class="filter=='Ceka se neka funkcionalnost' ? 'bg-gray-200' : 'bg-none'"
                                >

                                    Ceka se neka funkcionalnost
                                </span>
                                <span

                                    class="p-3 text-lg font-semibold cursor-pointer"
                                    @click="filter='U procesu'"
                                    :class="filter=='U procesu' ? 'bg-gray-200' : 'bg-none'"
                                >

                                    Ne javljalju se
                                </span>
                            </div>
                        </li>
                        <li v-if="izabranoTrziste">
                            <div class=" space-x-8 sm:-my-px sm:flex items-center pt-6 cursor-pointer"
                            >
                                <div class="flex w-full ">
                                    <Link :href="route('implementirano')" class="text-xl text-black ">Implementirani</Link>
                                </div>

                            </div>
                        </li>
                    </ul>
                </div>
                <main class="flex-grow-1">
                    <slot/>
                </main>
            </div>


        </div>
    </div>
</template>

