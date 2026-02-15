<script setup lang="ts">
import { ToastProvider, type ToastProviderProps } from 'radix-vue'
import { computed } from 'vue'

const props = defineProps<ToastProviderProps>()

const delegatedProps = computed(() => {
  const { ...delegated } = props

  return delegated
})
</script>

<template>
  <ToastProvider v-bind="delegatedProps">
    <slot />
  </ToastProvider>
</template>
