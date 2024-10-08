<script setup>
import {Link, usePage} from '@inertiajs/vue3';

const props = defineProps({
    markets: {
        type: Array
    }
})
const page = usePage()
const hasMarket = (trziste) => {
    if (page.props.auth.user.role_id === 1) {
        return true; // Admin role always has access
    }
    return page.props.user.markets.some(m => m.id == trziste.id);
}
</script>

<template>
    <div class="py-12">
        <div class="w-full grid grid-cols-4 justify-center items-start  text-xl font-semibold ">
            <div class="bg-[#AF9B46] rounded-lg p-6 w-72">
                <h2 class="text-lg font-semibold text-black mb-4 font-roboto">Tržišta</h2>
                <div class="space-y-4">
                    <Link :href="route('trzista.create')" as="button"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">Dodaj
                        novo tržište
                    </Link>
                    <Link :href="route('trzista.create')" as="button"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">
                        Pregled trzista
                    </Link>
                    <Link
                        v-for="market in markets"
                        as="button"
                        class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm"
                        :key="market.id"
                        :href="route('trziste.show', {id:market.id})"
                        :class="hasMarket(market) ? 'bg-green-500' : 'bg-red-500'"
                    >{{ market.ime_trzista }}
                    </Link>

                </div>
            </div>
            <div
                v-if="$page.props.auth.user.role_id===1"
                class="bg-white shadow-lg rounded-lg p-6 w-72">
                <!-- Menu Header -->
                <h2 class="text-lg font-semibold text-gray-800 mb-4 font-roboto">Users</h2>

                <!-- Button Group -->
                <div class="space-y-4">
                    <Link :href="route('korisnici')"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm"
                          as="button">Administracija postoejcih korisnika
                    </Link>
                    <Link :href="route('dodaj-korisnika')" as="button"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">Novi
                        korisnik
                    </Link>

                </div>
            </div>
            <div
                class="bg-white shadow-lg rounded-lg p-6 w-72">
                <!-- Menu Header -->
                <h2 class="text-lg font-semibold text-gray-800 mb-4 font-roboto">Funkcionalnosti</h2>

                <!-- Button Group -->
                <div class="space-y-4">
                    <Link :href="route('paket-funkcionalnost.create')"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm"
                          as="button">Dodaj funkcionalnost u paket
                    </Link>
                    <Link :href="route('nova-funkctionalnost')" as="button"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">
                        Dodaj funkcionalnost van paketa
                    </Link>

                </div>
            </div>
            <div
                class="bg-white shadow-lg rounded-lg p-6 w-72">
                <!-- Menu Header -->
                <h2 class="text-lg font-semibold text-gray-800 mb-4 font-roboto">Paketi</h2>

                <!-- Button Group -->
                <div class="space-y-4">
                    <Link :href="route('paket.create')"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm"
                          as="button">Dodavanje novog paketa
                    </Link>
                    <Link :href="route('paket.edit')" as="button"
                          class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">Izmena postojeceg
                    </Link>

                </div>
            </div>
        </div>
    </div>
</template>
