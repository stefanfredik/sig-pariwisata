<script setup>
import { Link } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { cn } from '@/lib/utils'

defineProps({
    links: Array,
})
</script>

<template>
    <div v-if="links.length > 3" class="p-4 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-slate-500 dark:text-slate-400 order-2 sm:order-1">
             <!-- Showing X to Y of Z results is handled in parent usually -->
        </div>
        <div class="flex items-center gap-2 order-1 sm:order-2">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-medium text-slate-400 opacity-50 cursor-not-allowed flex items-center gap-1"
                >
                     <span v-if="link.label.includes('Previous')"><ChevronLeft class="h-4 w-4 mr-1"/> Previous</span>
                     <span v-else-if="link.label.includes('Next')">Next <ChevronRight class="h-4 w-4 ml-1"/></span>
                     <span v-else v-html="link.label"></span>
                </div>
                <Link
                    v-else
                    :href="link.url"
                    preserve-scroll
                    :class="cn(
                        'flex items-center justify-center rounded-lg text-sm transition-colors',
                        link.active 
                            ? 'w-8 h-8 bg-primary text-white font-bold' 
                            : 'w-8 h-8 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700',
                        (link.label.includes('Previous') || link.label.includes('Next')) && 'w-auto px-3 py-1.5 h-auto border border-slate-200 dark:border-slate-700 hover:bg-slate-50 font-medium'
                    )"
                >
                     <span v-if="link.label.includes('Previous')" class="flex items-center gap-1"><ChevronLeft class="h-4 w-4"/> Previous</span>
                     <span v-else-if="link.label.includes('Next')" class="flex items-center gap-1">Next <ChevronRight class="h-4 w-4"/></span>
                     <span v-else v-html="link.label"></span>
                </Link>
            </template>
        </div>
    </div>
</template>
