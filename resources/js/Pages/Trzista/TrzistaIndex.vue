<script setup>
import {Link, usePage} from '@inertiajs/vue3';
import {computed, ref} from "vue";

const showTrzisteDropdown = ref(false);
const showUserDropdown = ref(false);
const props = defineProps({
    markets: Array,
})
const page = usePage()
const hasMarket = (trziste) => {
    if (page.props.auth.user.role_id === 1) {
        return true; // Admin role always has access
    }
    return page.props.user.markets.some( m => m.id == trziste.id);
}
</script>

<template>


    <div class="py-12">
        <div class="flex items-center justify-center gap-40 text-xl font-semibold">
            <div class="bg-white py-4 px-8 shadow  flex gap-4 items-center relative"
                 @mouseover="showTrzisteDropdown = true"
                 @mouseleave="showTrzisteDropdown = false">
                <Link :href="route('trzista.index')" class="text-current no-underline">
                    Trenutna trzista
                </Link>
                <span class="relative dropdown-toggle">
                    </span>
                <div v-show="showTrzisteDropdown"
                     class="absolute top-[65px]  mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-20">
                    <Link
                        v-for="market in markets"
                        :key="market.id"
                        :href="route('trziste.show', {id:market.id})"
                        :class="hasMarket(market) ? 'bg-green-500' : 'bg-red-500'"
                        class="block px-4 py-2 text-sm capitalize text-gray-700 hover:bg-blue-500 hover:text-white text-current no-underline">
                        {{ market.ime_trzista }}
                    </Link>


                </div>
            </div>
            <Link :href="route('trzista.create')" class="bg-white py-4 px-8 shadow  text-current no-underline "
            >
                Dodaj novo trziste
            </Link>
        </div>
    </div>
</template>
