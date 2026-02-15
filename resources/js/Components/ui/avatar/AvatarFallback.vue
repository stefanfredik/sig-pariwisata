<script setup lang="ts">
import { type HTMLAttributes } from 'vue'
import { AvatarFallback, type AvatarFallbackProps } from 'radix-vue'

const props = defineProps<AvatarFallbackProps & { class?: HTMLAttributes['class'] }>()
</script>

<template>
  <AvatarFallback v-bind="props" :class="props.class" class="flex h-full w-full items-center justify-center rounded-full bg-muted">
    <slot />
  </AvatarFallback>
</template>
