<script setup lang="ts">
import { type HTMLAttributes } from 'vue'
import { AvatarRoot } from 'radix-vue'
import { type VariantProps } from 'class-variance-authority'
import { cn } from '@/lib/utils'

const props = withDefaults(defineProps<{
  class?: HTMLAttributes['class']
  size?: VariantProps<typeof avatarVariants>['size']
  shape?: VariantProps<typeof avatarVariants>['shape']
}>(), {
  size: 'sm',
  shape: 'circle',
})
</script>

<script lang="ts">
import { cva } from 'class-variance-authority'

export const avatarVariants = cva(
  'relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full',
  {
    variants: {
      size: {
        sm: 'h-10 w-10',
        base: 'h-16 w-16',
        lg: 'h-32 w-32',
      },
      shape: {
        circle: 'rounded-full',
        square: 'rounded-md',
      },
    },
  },
)
</script>

<template>
  <AvatarRoot :class="cn(avatarVariants({ size, shape }), props.class)">
    <slot />
  </AvatarRoot>
</template>
