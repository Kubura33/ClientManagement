<script setup>
import {ref} from 'vue';
import {Link, router, useRemember} from "@inertiajs/vue3";

const userMenu = ref(false);

const izabranoTrziste = ref("")
const trzista = [
    { id: 1, name: "Srbija" },
    { id: 2, name: "Bosna" },
    { id: 3, name: "Slovenija" }
];



const header = ref("")
router.on('navigate', () => {
    console.log("I work!!!")
    if(route().current('novi-klijent')){
        header.value = " - Unos nove firme"
    }
    else if(route().current('implementirano')){
        header.value = " - Implementirani"
    }
    else if(route().current('index')){
        header.value = ""
    }
})
if(route().current('novi-klijent')){
    header.value = " - Unos nove firme"
}
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Page Heading -->
            <header class="bg-white shadow"     >
                <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight p-2">
                        B2B Support - Evidencija klijenata - {{ $page.props.auth.user.username }} {{header}}


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
                                <Link

                                    v-for="(trziste, index) in trzista" :key="trziste.id"
                                    class="p-3 text-lg font-semibold cursor-pointer"
                                    @click="izabranoTrziste=trziste.name"
                                    :class="izabranoTrziste==trziste.name ? 'bg-gray-200' : 'bg-none'"
                                    :href="route('index', {id: trziste.id})"
                                >

                                    {{ trziste.name }}

                                </Link>

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

