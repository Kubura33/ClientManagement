<script setup>
import { Link} from '@inertiajs/vue3';
</script>

<template>
        <div class="py-12">
            <div class="flex items-stretch justify-center gap-40 text-xl font-semibold ">
                <div class="bg-white shadow-lg rounded-lg p-6 w-64">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 font-roboto">Tržišta</h2>
                    <div class="space-y-4">
                        <Link :href="route('trzista.index')" class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm" as="button">Trenutna tržišta</Link>
                        <Link :href="route('trzista.create')" as="button" class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">Dodaj novo tržište</Link>
                        <Link :href="route('trzista.create')" as="button" class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">Pregled trzista</Link>
                    </div>
                </div>
                <div
                    v-if="$page.props.auth.user.role_id===1"
                    class="bg-white shadow-lg rounded-lg p-6 w-64">
                    <!-- Menu Header -->
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 font-roboto">Users</h2>

                    <!-- Button Group -->
                    <div class="space-y-4">
                        <Link :href="route('korisnici')" class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm" as="button">Administracija postoejcih korisnika</Link>
                        <Link :href="route('dodaj-korisnika')" as="button" class="w-full px-1 py-2 text-blue-600 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">Novi korisnik</Link>

                    </div>
                </div>
            </div>
        </div>
</template>
