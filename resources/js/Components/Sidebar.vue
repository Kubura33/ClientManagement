<script setup>
import {computed, ref} from "vue";
import {Link, router, usePage} from "@inertiajs/vue3";

const showDropdown = ref(false)
const page = usePage()
const role = page.props.auth.user.role_id
const dropdown = () => {
    showDropdown.value = !showDropdown.value
}
const props = defineProps({
    trzista: Array,
    izabrano: String,
    otherCompanies: Array,
})
const trenutnoTrziste = ref(props.izabrano ?? "")
const goBack = () => {
    window.history.back()
}

const logout = () => {
    router.post(route('logout'))
}

</script>

<template>
    <div
        class="sidebar relative top-0 bottom-0 lg:left-0 min-h-screen p-2  overflow-y-auto text-center bg-gray-900"
        style="min-width: 300px; max-width: 300px"
    >
        <div class="fixed" style="width: 290px;">
            <div class="text-gray-100 text-xl">
                <div class="p-2.5 mt-1 flex align-items-end">
                    <h1 class="text-center text-gray-200  cursor-pointer" @click="goBack">&larr;</h1>

                </div>

                <div class="my-2 bg-gray-600 h-[1px]"></div>
            </div>

            <Link
                :href="route('dashboard')"
                class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white no-underline"
            >
                <i class="bi bi-house-door-fill"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Pocetna</span>
            </Link>
            <Link :href="route('trzista.index')"
                class="p-2.5 mt-3 flex items-center text-current no-underline rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white"
            >
                <i class="bi bi-flag-fill"></i>
                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-200 font-bold">Trzista</span>
                </div>
            </Link>



<!--                    <Link v-if="otherCompanies && otherCompanies[trziste.id]" class="text-lg no-underline text-current" :href="route('contract.show', {id: otherCompanies[trziste.id].id})">+</Link>-->


            <Link
                v-if="role === 1"
                :href="route('dodaj-korisnika')"
                class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white no-underline"
            >
                <i class="bi bi-person-add"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Dodavanje korisnika</span>
            </Link>
            <div class="my-4 bg-gray-600 h-[1px]"></div>
            <Link :href="route('profile.edit')"
                class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white no-underline text-current"
            >
                <i class="bi bi-person-circle"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Profil</span>
            </Link>
            <div
                @click="logout"
                class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-blue-600 text-white no-underline text-current"
            >
                <i class="bi bi-box-arrow-in-right"></i>
                <span class="text-[15px] ml-4 text-gray-200 font-bold">Logout</span>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
