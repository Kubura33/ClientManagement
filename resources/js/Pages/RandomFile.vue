<script setup>

import TextInput from "@/Components/TextInput.vue";
</script>

<template>
    <div class="mt-1 flex flex-col  ">

        <div class="flex flex-col gap-2 mt-4">
            <label for="">Dodaj custom funkcionalnost (uneti pa pritisnuti enter)</label>
            <TextInput
                v-model="funcToAdd"
                @keydown.enter.prevent="addCustomFunc"
                type="text"
                class="mt-1 block w-full"
            />
        </div>
        <div class="mt-2">
            <span>Custom funkcionalnosti koje ste dodali: </span>
            <div class="bg-white shadow rounded p-2 mt-2">
                <div class="flex justify-between items-center"
                     v-if="form.customFunkcionalnosti.length>0"
                     v-for="cf in form.customFunkcionalnosti">
                    <span> {{ cf.funkcionalnost }}</span>
                    <span class="text-2xl text-red-600 font-semibold cursor-pointer"
                          @click="removeCustomFunc(cf)"> - </span>
                </div>
                <span v-else>Trenutno nisu dodate custom funkcionalnosti</span>
            </div>

        </div>
        <hr>
    </div>
    <div id="funkcionalnosti" class="w-full">
        <div class="mt-3">
            <span>Funkcionalnosti koje vec postoje u sistemu:</span>
        </div>
        <div class="bg-white shadow rounded p-3 mt-2">

            <div class="mt-1 overflow-y-auto max-h-[300px]">
                <div class="flex items-center justify-between gap-4 " v-for="func in existingFuncs"
                     :key="func.id">
                    <div class="flex flex-row-reverse gap-2">
                        <label class="form-check-label" :for="func.funkcionalnost">
                            {{ func.funkcionalnost }}
                        </label>
                        <input
                            @change="toggleFunctionality(func)"
                            :checked="doesPackageContainFunc(func)"

                            class="form-check-input border-2 border-black" type="checkbox" value=""
                            :id="func.funkcionalnost">
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
