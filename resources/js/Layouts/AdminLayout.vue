<script setup lang="ts">
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed, watch } from 'vue'
import { Button } from '@/Components/ui/button'
import { Sheet, SheetContent, SheetTrigger, SheetHeader, SheetTitle, SheetDescription } from '@/Components/ui/sheet'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/Components/ui/dropdown-menu'
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar'
import { Toaster } from '@/Components/ui/toast'
import { useToast } from '@/Components/ui/toast/use-toast'
import { 
  LayoutDashboard, 
  Map as MapIcon, 
  Mountain, 
  Calendar, 
  Coffee, 
  MessageSquare, 
  Menu, 
  LogOut, 
  User 
} from 'lucide-vue-next'
import { cn } from '@/lib/utils'

const page = usePage()
const user = computed(() => page.props.auth.user)
const { toast } = useToast()

const navSections = [
  {
    title: 'General',
    items: [
      { label: 'Dashboard', route: 'admin.dashboard', icon: LayoutDashboard, pattern: 'admin.dashboard' },
    ]
  },
  {
    title: 'Tourism Management',
    items: [
      { label: 'Objek Wisata', route: 'admin.objek-wisata.index', icon: Mountain, pattern: 'admin.objek-wisata.*' },
      { label: 'Fasilitas Wisata', route: 'admin.fasilitas-wisata.index', icon: Coffee, pattern: 'admin.fasilitas-wisata.*' },
      { label: 'Events & Agenda', route: 'admin.events.index', icon: Calendar, pattern: 'admin.events.*' },
      { label: 'Data Kecamatan', route: 'admin.kecamatan.index', icon: MapIcon, pattern: 'admin.kecamatan.*' },
    ]
  },
  {
    title: 'Community',
    items: [
      { label: 'Moderasi Review', route: 'admin.reviews.index', icon: MessageSquare, pattern: 'admin.reviews.*' },
    ]
  }
]

const isUrl = (pattern: string) => {
    // using Inertia's route helper or checking URL
    // @ts-ignore
    return route().current(pattern)
}

const logout = () => {
    router.post('/logout')
}

// Watch for flash messages
watch(() => page.props.flash, (flash: any) => {
    if (flash?.message) {
        toast({
            title: 'Berhasil',
            description: flash.message,
            variant: 'default',
        })
    }
    if (flash?.error) {
        toast({
            title: 'Error',
            description: flash.error,
            variant: 'destructive',
        })
    }
}, { deep: true, immediate: true })
</script>

<template>
  <div class="flex min-h-screen w-full bg-muted/40">
    <!-- Desktop Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-10 hidden w-64 flex-col border-r bg-background sm:flex font-sans">
      <div class="flex h-16 items-center gap-2 border-b px-6">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground shadow-lg shadow-primary/20">
          <Mountain class="h-5 w-5" />
        </div>
        <span class="text-lg font-black tracking-tight uppercase">SIG <span class="text-primary italic">Wisata</span></span>
      </div>
      
      <nav class="grid gap-6 px-4 py-6 overflow-y-auto">
        <div v-for="section in navSections" :key="section.title" class="space-y-2">
            <div class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">{{ section.title }}</div>
            
            <div class="grid gap-1">
                <Link 
                    v-for="item in section.items" 
                    :key="item.route" 
                    :href="route(item.route)"
                    :class="cn(
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 transition-all group relative',
                        isUrl(item.pattern) 
                            ? 'bg-primary text-white shadow-md shadow-primary/20 font-bold' 
                            : 'text-slate-500 hover:text-primary hover:bg-slate-50 font-medium'
                    )"
                >
                    <component :is="item.icon" class="h-4 w-4" :class="isUrl(item.pattern) ? 'text-white' : 'group-hover:text-primary transition-colors'" />
                    <span class="text-sm tracking-tight">{{ item.label }}</span>
                    <div v-if="isUrl(item.pattern)" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-4 bg-white rounded-r-full"></div>
                </Link>
            </div>
        </div>
      </nav>

      <div class="mt-auto p-4 border-t bg-slate-50/50">
         <div class="flex items-center gap-3 px-2">
            <Avatar class="border-2 border-white shadow-sm">
                <AvatarImage :src="user.avatar_url || ''" />
                <AvatarFallback class="bg-primary text-white font-bold">{{ user.name.charAt(0) }}</AvatarFallback>
            </Avatar>
            <div class="grid gap-0.5 text-xs">
                <span class="font-bold text-slate-900">{{ user.name }}</span>
                <span class="text-slate-500 capitalize text-[10px] font-medium tracking-wider">{{ user.role }}</span>
            </div>
         </div>
      </div>
    </aside>

    <div class="flex flex-col sm:gap-4 sm:py-4 sm:pl-64 w-full">
      <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-background px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
        <Sheet>
          <SheetTrigger as-child>
            <Button size="icon" variant="outline" class="sm:hidden rounded-xl border-slate-200">
              <Menu class="h-5 w-5" />
              <span class="sr-only">Toggle Menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="left" class="sm:max-w-xs">
            <SheetHeader class="text-left mb-8">
                <SheetTitle class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <Mountain class="h-5 w-5" />
                    </div>
                    <span class="font-black tracking-tight uppercase">SIG <span class="text-primary italic">Wisata</span></span>
                </SheetTitle>
            </SheetHeader>
            <nav class="grid gap-6">
                <div v-for="section in navSections" :key="section.title" class="space-y-3">
                    <div class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ section.title }}</div>
                    <div class="grid gap-1">
                        <Link 
                            v-for="item in section.items" 
                            :key="item.route" 
                            :href="route(item.route)"
                            :class="cn(
                                'flex items-center gap-4 px-3 py-3 rounded-xl transition-all',
                                isUrl(item.pattern) ? 'bg-primary text-white font-bold' : 'text-slate-600 font-medium'
                            )"
                        >
                            <component :is="item.icon" class="h-5 w-5" />
                            {{ item.label }}
                        </Link>
                    </div>
                </div>
            </nav>
          </SheetContent>
        </Sheet>
        
        <!-- Breadcrumb / Search Placeholder -->
        <div class="relative ml-auto flex-1 md:grow-0">
           <!-- Search could go here -->
        </div>

        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="secondary" size="icon" class="rounded-full">
              <User class="h-5 w-5" />
              <span class="sr-only">Toggle user menu</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuLabel>My Account</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem>Settings</DropdownMenuItem>
            <DropdownMenuItem>Support</DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem @click="logout" class="text-destructive focus:text-destructive cursor-pointer">
                Logout
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </header>
      
      <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8 min-w-0">
           <slot />
      </main>
    </div>
    <Toaster />
  </div>
</template>
