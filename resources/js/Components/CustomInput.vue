<script setup>
const props = defineProps({
    modelValue: {
        type: String,
        default: ""
    }
})

const emit = defineEmits(['update:modelValue']);
const handleInput = (event) => {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <input
        :value="modelValue"
        @input="handleInput"
        type="text"  class="block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
</template>

<style scoped>

</style>
