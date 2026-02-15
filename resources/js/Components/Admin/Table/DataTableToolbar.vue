<script setup lang="ts">
import { useSlots } from 'vue'
import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'
import { X, Search, Filter, ArrowUpDown, Check } from 'lucide-vue-next'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
} from '@/Components/ui/dropdown-menu'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import { cn } from '@/lib/utils'

interface SortOption {
  label: string
  value: string
}

const props = defineProps<{
    search?: string
    placeholder?: string
    sortOptions?: SortOption[]
    currentSort?: string
    enableFilter?: boolean
}>()

const emit = defineEmits(['update:search', 'update:sort', 'reset'])
const slots = useSlots()

const handleSort = (value: string) => {
    emit('update:sort', value)
}
</script>

<template>
  <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-4 items-center justify-between">
    <div class="relative w-full sm:max-w-md">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 h-5 w-5" />
        <Input 
          :model-value="search" 
          @update:model-value="$emit('update:search', $event)" 
          :placeholder="placeholder || 'Search...'" 
          class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-slate-400 shadow-none h-10"
        />
        <Button 
            v-if="search"
            variant="ghost" 
            @click="$emit('reset')"
            class="absolute right-2 top-1/2 -translate-y-1/2 h-6 w-6 p-0 text-slate-400 hover:text-red-500" 
            title="Clear Search"
        >
             <X class="h-4 w-4" />
        </Button>
    </div>
    
    <div class="flex items-center gap-2 w-full sm:w-auto">
       <Popover v-if="slots.filters">
        <PopoverTrigger as-child>
            <Button variant="outline" class="flex-1 sm:flex-none px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium flex items-center justify-center gap-2 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors shadow-none h-10">
                <Filter class="h-4 w-4" />
                Filter
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80 p-4" align="end">
            <div class="space-y-4">
                <h4 class="font-medium leading-none text-slate-900 dark:text-white mb-2">Filters</h4>
                <slot name="filters" />
                <div class="pt-2 flex justify-end">
                    <!-- Optional: Add specific filter actions here if needed, generic valid for now -->
                </div>
            </div>
        </PopoverContent>
       </Popover>
       
       <DropdownMenu v-if="sortOptions && sortOptions.length > 0">
        <DropdownMenuTrigger as-child>
            <Button variant="outline" class="flex-1 sm:flex-none px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium flex items-center justify-center gap-2 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors shadow-none h-10">
                <ArrowUpDown class="h-4 w-4" />
                Sort
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-48">
            <DropdownMenuLabel>Sort by</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuRadioGroup :model-value="currentSort" @update:model-value="handleSort">
                <DropdownMenuRadioItem v-for="option in sortOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                </DropdownMenuRadioItem>
            </DropdownMenuRadioGroup>
        </DropdownMenuContent>
       </DropdownMenu>
    </div>
  </div>
</template>
