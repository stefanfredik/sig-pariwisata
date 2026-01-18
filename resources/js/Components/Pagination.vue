<template>
    <div v-if="links.length > 3" class="flex flex-wrap -mb-1">
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-xl"
                v-html="link.label"
            />
            <Link
                v-else
                class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                :class="[
                    link.active 
                    ? 'bg-primary text-white border-primary shadow-sm shadow-primary/20 hover:bg-primary/90' 
                    : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50 hover:border-gray-300'
                ]"
                :href="link.url"
                v-html="link.label"
                preserve-scroll
            />
        </template>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>
