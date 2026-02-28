<script setup lang="ts">
import { Link, Head } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import { ArrowLeft } from 'lucide-vue-next'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineProps<{
    title: string
    description?: string
    backRoute: string
    backLabel?: string
}>()
</script>

<template>
    <Head :title="title" />
    <AdminLayout>
        <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white uppercase">
                        {{ title }}
                    </h2>
                    <p v-if="description" class="mt-1 text-sm text-slate-500 font-medium italic">
                        {{ description }}
                    </p>
                </div>
                <Button variant="outline" size="sm" class="rounded-xl shadow-sm border-slate-200" as-child>
                    <Link :href="backRoute">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        {{ backLabel || 'Kembali' }}
                    </Link>
                </Button>
            </div>

            <!-- Main Content (Form) -->
            <div class="space-y-8">
                <slot />
            </div>
        </div>
    </AdminLayout>
</template>
