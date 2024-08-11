<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import {computed, ref} from "vue";

const procesMenu = ref(false);
const filter = ref('')
const page = usePage()
const canAdd = computed(() => {
    return page.props.auth.user.role_id != "4"
})
</script>

<template>
    <div class="w-full h-full">
        <div class="flex mt-20 items-center justify-center gap-20 text-2xl font-semibold">
            <div>
                <Link
                    v-if="canAdd"
                    :href="route('novi-klijent')" class="bg-white p-6 shadow no-underline text-current">
                    Novi klijent
                </Link>
            </div>

            <div class="">
                <button class="bg-white p-6 shadow  dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    U procesu
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><Link :href="route('filter', {filter:1})" class="dropdown-item" >U toku</Link></li>
                    <li><Link :href="route('filter', {filter:2})" class="dropdown-item" >Čeka se neka funkcionalnost</Link></li>
                    <li><Link :href="route('filter', {filter:3})" class="dropdown-item" >Ne javljalju se</Link></li>
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
