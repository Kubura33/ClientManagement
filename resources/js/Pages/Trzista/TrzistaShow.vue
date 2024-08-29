<script setup>

import {Link} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    market: Object
})

const showClientDropdown = ref(false)
const showPackageDropdown = ref(false)
const showFunctionalityDropdown = ref(false)
</script>

<template>
    <div class="py-12">
        <div class="flex items-center justify-center gap-40 text-xl font-semibold ">
            <div class="bg-white py-4 px-8 shadow  flex gap-4 items-center relative"
                 @mouseover="showClientDropdown = true"
                 @mouseleave="showClientDropdown = false">
                <Link :href="route('index', {id: market.id})" class="text-current no-underline">
                    Klijent
                </Link>
                <span class="relative dropdown-toggle">
                    </span>
                <div v-show="showClientDropdown"
                     class="absolute top-[65px]  mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                    <Link
                        method="GET"
                        :href="route('novi-klijent.create', {id:market.id})"
                        class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white text-current no-underline">
                        Dodaj novog klijenta
                    </Link>
                    <Link :href="route('filter', {filter:4})"
                          class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white text-current no-underline">
                        Obrada postojeceg
                    </Link>

                </div>
            </div>
            <div class="bg-white py-4 px-8 shadow  flex gap-4 items-center relative cursor-pointer"
                 v-if="$page.props.auth.user.role_id !=4"
                 @mouseover="showPackageDropdown = true"
                 @mouseleave="showPackageDropdown = false">
                <span class="text-current no-underline">
                    Paket
                </span>
                <span class="relative dropdown-toggle">
                    </span>
                <div v-show="showPackageDropdown"
                     class="absolute top-[65px]  mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                    <Link :href="route('paket.create', {market:market.id})"
                          class="text-current no-underline block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white">
                        Dodavanje novog paketa
                    </Link>
                    <Link :href="route('paket.edit', {market:market.id})"
                          class="text-current no-underline block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white">
                        Izmena postojeceg
                    </Link>

                </div>
            </div>
            <div class="bg-white py-4 px-8 shadow  flex gap-4 items-center relative"
                 v-if="$page.props.auth.user.role_id !=4"
                 @mouseover="showFunctionalityDropdown = true"
                 @mouseleave="showFunctionalityDropdown = false">
                <Link :href="route('dodaj-korisnika')" class="text-current no-underline">
                    Funkcionalnost
                </Link>
                <span class="relative dropdown-toggle">
                    </span>
                <div v-show="showFunctionalityDropdown"
                     class="absolute top-[65px]  mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                    <Link :href="route('paket-funkcionalnost.create', {market: market.id})"
                          class="text-current no-underline block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white">
                        Dodaj funkcionalnost u paket
                    </Link>
                    <Link :href="route('nova-funkctionalnost', {market: market.id})"
                          class="text-current no-underline block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white">
                        Dodaj novu funkcionalnost - bez paketa
                    </Link>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
