import { cva, type VariantProps } from 'class-variance-authority'

export { default as Avatar, avatarVariants } from './Avatar.vue'
export { default as AvatarImage } from './AvatarImage.vue'
export { default as AvatarFallback } from './AvatarFallback.vue'

// Removed inline definition, exported from component
export type AvatarVariants = VariantProps<typeof avatarVariants>
// Wait, typeof avatarVariants refers to import.
