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
        <div class="flex items-center justify-center gap-10 text-xl font-semibold ">
            <Link :href="route('novi-klijent.create', {id: market.id})"
                  class="w-72 px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm"
                  as="button">Novi klijent
            </Link>
            <Link :href="route('index', {id: market.id})"
                  class="w-72 px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm"
                  as="button">Obrada postojeceg klijenta
            </Link>
        </div>
    </div>
</template>

<style scoped>

</style>
