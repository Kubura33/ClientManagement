<script setup>
import {Link} from "@inertiajs/vue3";
import {ref} from "vue";

const procesMenu = ref(false);
const filter = ref('')
</script>

<template>
    <div class="w-full h-full">
        <div class="flex mt-20 items-center justify-center gap-20 text-2xl font-semibold">
            <div>
                <Link :href="route('novi-klijent')" class="bg-white p-6 shadow no-underline text-current">
                    Novi klijent
                </Link>
            </div>

            <div class="">
                <button class="bg-white p-6 shadow  dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    U procesu
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#">U toku</a></li>
                    <li><a class="dropdown-item" href="#">Čeka se neka funkcionalnost</a></li>
                    <li><a class="dropdown-item" href="#">Ne javljalju se</a></li>
                </ul>
            </div>
            <div>
                <Link :href="route('implementirano')" class="bg-white p-6 shadow no-underline text-current">
                    Implementirani
                </Link>
            </div>

        </div>
    </div>

</template>

<style scoped>

</style>
