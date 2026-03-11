<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue'
import { X, ChevronLeft, ChevronRight, Maximize2, ZoomIn, ZoomOut } from 'lucide-vue-next'

const props = defineProps<{
    images: string[] // Full URLs
    isOpen: boolean
    initialIndex?: number
}>()

const emit = defineEmits(['close'])

const currentIndex = ref(props.initialIndex || 0)
const isZoomed = ref(false)

const close = () => {
    isZoomed.value = false
    emit('close')
}

const next = () => {
    isZoomed.value = false
    currentIndex.value = (currentIndex.value + 1) % props.images.length
}

const prev = () => {
    isZoomed.value = false
    currentIndex.value = (currentIndex.value - 1 + props.images.length) % props.images.length
}

const handleKeydown = (e: KeyboardEvent) => {
    if (!props.isOpen) return
    if (e.key === 'Escape') close()
    if (e.key === 'ArrowRight') next()
    if (e.key === 'ArrowLeft') prev()
}

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        document.body.style.overflow = 'hidden'
        currentIndex.value = props.initialIndex || 0
    } else {
        document.body.style.overflow = ''
    }
})

onMounted(() => window.addEventListener('keydown', handleKeydown))
onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown)
    document.body.style.overflow = ''
})
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 backdrop-blur-sm select-none">
            <!-- Close Button -->
            <button 
                @click="close"
                class="absolute top-6 right-6 z-[10000] p-3 text-white/70 hover:text-white hover:bg-white/10 rounded-full transition-all active:scale-95"
            >
                <X class="w-8 h-8" />
            </button>

            <!-- Navigation Controls -->
            <div v-if="images.length > 1" class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-4 md:px-10 z-[10000] pointer-events-none">
                <button 
                    @click="prev"
                    class="p-4 text-white/50 hover:text-white hover:bg-white/10 rounded-full transition-all active:scale-95 pointer-events-auto bg-black/20 backdrop-blur-md"
                >
                    <ChevronLeft class="w-10 h-10" />
                </button>
                <button 
                    @click="next"
                    class="p-4 text-white/50 hover:text-white hover:bg-white/10 rounded-full transition-all active:scale-95 pointer-events-auto bg-black/20 backdrop-blur-md"
                >
                    <ChevronRight class="w-10 h-10" />
                </button>
            </div>

            <!-- Image Container -->
            <div class="relative w-full h-full flex items-center justify-center p-4 md:p-12 overflow-hidden">
                <Transition
                    mode="out-in"
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div :key="currentIndex" class="relative max-w-full max-h-full flex items-center justify-center">
                        <img 
                            :src="images[currentIndex]" 
                            class="max-w-full max-h-[85vh] object-contain shadow-2xl transition-all duration-500 cursor-zoom-in"
                            :class="{ 'scale-125': isZoomed }"
                            @click="isZoomed = !isZoomed"
                        />
                        
                        <!-- Image Info -->
                        <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2">
                             <div class="px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white text-xs font-black uppercase tracking-widest">
                                {{ currentIndex + 1 }} / {{ images.length }}
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>

            <!-- Thumbnails Strip -->
            <div v-if="images.length > 1" class="absolute bottom-8 inset-x-0 flex justify-center gap-3 px-6 overflow-x-auto no-scrollbar">
                <button 
                    v-for="(img, idx) in images" 
                    :key="idx"
                    @click="currentIndex = idx"
                    class="relative flex-shrink-0 w-16 md:w-20 aspect-video rounded-lg overflow-hidden border-2 transition-all duration-300"
                    :class="currentIndex === idx ? 'border-primary scale-110 shadow-lg' : 'border-transparent opacity-40 hover:opacity-100'"
                >
                    <img :src="img" class="w-full h-full object-cover" />
                </button>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
