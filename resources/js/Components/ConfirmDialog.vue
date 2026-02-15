<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { AlertTriangle, Info } from 'lucide-vue-next'

interface Props {
  open: boolean
  title?: string
  description?: string
  confirmText?: string
  cancelText?: string
  variant?: 'destructive' | 'primary'
  loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Konfirmasi Tindakan',
  description: 'Apakah Anda yakin ingin melanjutkan tindakan ini? Tindakan ini mungkin tidak dapat dibatalkan.',
  confirmText: 'Lanjutkan',
  cancelText: 'Batal',
  variant: 'destructive',
  loading: false,
})

const emit = defineEmits<{
  (e: 'update:open', value: boolean): void
  (e: 'confirm'): void
  (e: 'cancel'): void
}>()

const handleConfirm = () => {
  emit('confirm')
}

const handleCancel = () => {
  emit('update:open', false)
  emit('cancel')
}
</script>

<template>
  <Dialog :open="open" @update:open="(val: boolean) => emit('update:open', val)">
    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <div class="flex items-center gap-4">
          <div 
            :class="[
              'p-3 rounded-2xl shrink-0',
              variant === 'destructive' ? 'bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400'
            ]"
          >
            <AlertTriangle v-if="variant === 'destructive'" class="w-6 h-6" />
            <Info v-else class="w-6 h-6" />
          </div>
          <div>
            <DialogTitle>{{ title }}</DialogTitle>
            <DialogDescription class="mt-1.5">
              {{ description }}
            </DialogDescription>
          </div>
        </div>
      </DialogHeader>
      
      <DialogFooter class="mt-6 gap-2">
        <Button 
          variant="outline" 
          @click="handleCancel"
          :disabled="loading"
          class="rounded-xl"
        >
          {{ cancelText }}
        </Button>
        <Button 
          :variant="variant === 'destructive' ? 'destructive' : 'default'"
          @click="handleConfirm"
          :disabled="loading"
          class="rounded-xl min-w-[100px]"
        >
          <span v-if="loading" class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
          {{ confirmText }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
