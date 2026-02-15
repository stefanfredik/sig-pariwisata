<script setup lang="ts">
import { cn } from '@/lib/utils'
import { ToastRoot, useToast } from 'radix-vue'
import { type ToastProps, toastVariants } from '.'
import { computed } from 'vue'

const props = defineProps<ToastProps>()
const emits = defineEmits<{
  (e: 'update:open', value: boolean): void
}>()

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})
</script>

<template>
  <ToastRoot
    v-bind="delegatedProps"
    :class="cn(toastVariants({ variant }), props.class)"
    @update:open="emits('update:open', $event)"
  >
    <slot />
  </ToastRoot>
</template>
