<script setup>
import {reactive} from "vue";
import {router} from "@inertiajs/vue3";
import {data} from "autoprefixer";

const props = defineProps({
    users: Array,
    trzista: Array,
    roles: Array,
})
const isOnMarket = (trziste, user) => {
    return user.markets.some(m => m.id === trziste.id)
}


const usersToEdit = reactive(props.users.map(user => ({...user, markets: [...user.markets]})));

const toggleMarket = (trziste, userId) => {
    usersToEdit.forEach(user => {
        if (user.id === userId) {
            const marketIndex = user.markets.findIndex(m => m.id === trziste.id);
            if (marketIndex !== -1) {
                // If the market is already associated, remove it
                user.markets.splice(marketIndex, 1);
            } else {
                // If not, add it
                user.markets.push(trziste);
            }
        }

    });
};
const save = () => {
    router.post(route('korisnici'), {data:usersToEdit})
}
</script>

<template>
    <button class="btn btn-primary mb-4" @click.prevent="save">Sacuvaj</button>
    <div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">Username</th>
                <th scope="col">Role</th>
                <th scope="col">Trzista</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in usersToEdit">
                <th scope="row"> {{ user.username }}</th>
                <td>
                    <select
                        v-model="user.role_id"
                        class="form-select h-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                        aria-label="Default select example">
                        <option :value="role.id" v-for="role in roles" :key="role.id"> {{ role.role }}
                        </option>
                    </select>
                </td>
                <td>
                    <div class="grid grid-cols-1">
                        <div class="flex flex-row gap-2" v-for="trziste in trzista">
                            <label class="form-check-label">
                                {{ trziste.ime_trzista }}
                            </label>
                            <input
                                @change="toggleMarket(trziste, user.id)"
                                :checked="isOnMarket(trziste, user)"
                                class="form-check-input border-2 border-black" type="checkbox" value=""
                                :id="trziste.id">
                        </div>

                    </div>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</template>

<style scoped>

</style>
