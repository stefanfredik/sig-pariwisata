<template>
    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center">
                <div :class="['flex-shrink-0 p-3 rounded-lg', colorClass]">
                    <slot name="icon"></slot>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ label }}</dt>
                        <dd :class="['text-3xl font-black', valueColorClass]">{{ value }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    value: [String, Number],
    colorClass: {
        type: String,
        default: 'bg-blue-50 text-blue-600'
    },
    valueColorClass: {
        type: String,
        default: 'text-gray-900'
    }
});
</script>
