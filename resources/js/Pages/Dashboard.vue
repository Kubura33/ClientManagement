<script setup>
import { Link} from '@inertiajs/vue3';
import {ref} from "vue";

const showTrzisteDropdown = ref(false);
const showUserDropdown = ref(false);
</script>

<template>


        <div class="py-12">
            <div class="flex items-center justify-center gap-40 text-xl font-semibold ">
                <div class="bg-white py-4 px-8 shadow  flex gap-4 items-center relative"
                     @mouseover="showTrzisteDropdown = true"
                     @mouseleave="showTrzisteDropdown = false">
                    <Link :href="route('trzista.index')" class="text-current no-underline">
                        Trziste
                    </Link>
                    <span    class="relative dropdown-toggle">
                    </span>
                    <div v-show="showTrzisteDropdown" class="absolute top-[65px]  mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                        <Link :href="route('trzista.index')" class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white text-current no-underline">
                            Trenuta trzista
                        </Link>
                        <Link :href="route('trzista.create')" class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white text-current no-underline">
                            Dodaj novo trziste
                        </Link>

                    </div>
                </div>
                <div
                    v-if="$page.props.auth.user.role_id===1"
                    class="bg-white py-4 px-8 shadow  flex gap-4 items-center relative"
                     @mouseover="showUserDropdown = true"
                     @mouseleave="showUserDropdown = false">
                    <Link :href="route('dodaj-korisnika')" class="text-current no-underline">
                        User
                    </Link>
                    <span    class="relative dropdown-toggle">
                    </span>
                    <div v-show="showUserDropdown" class="absolute top-[65px]  mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                        <Link :href="route('korisnici')" class="text-current no-underline block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white">
                            Administracija postojecih korisnika
                        </Link>
                        <Link :href="route('dodaj-korisnika')" class="text-current no-underline block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white">
                            Novi korisnik
                        </Link>

                    </div>
                </div>
            </div>
        </div>
</template>
