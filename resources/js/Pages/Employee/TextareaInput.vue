<template>
    <div>
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <textarea
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :class="{
                'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500': error,
                'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500': !error
            }"
            class="mt-1 block w-full rounded-md shadow-sm sm:text-sm"
            :rows="rows"
            :placeholder="placeholder"
            :disabled="disabled"
            v-bind="$attrs"
        ></textarea>
        <p v-if="error" class="mt-2 text-sm text-red-600">
            {{ error }}
        </p>
        <p v-else-if="hint" class="mt-2 text-sm text-gray-500">
            {{ hint }}
        </p>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    label: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: ''
    },
    hint: {
        type: String,
        default: ''
    },
    rows: {
        type: [String, Number],
        default: 3
    },
    placeholder: {
        type: String,
        default: ''
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

defineEmits(['update:modelValue']);
</script>
