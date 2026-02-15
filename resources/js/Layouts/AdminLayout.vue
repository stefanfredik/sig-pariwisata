<script setup lang="ts">
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import { Button } from '@/Components/ui/button'
import { Sheet, SheetContent, SheetTrigger, SheetHeader, SheetTitle, SheetDescription } from '@/Components/ui/sheet'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/Components/ui/dropdown-menu'
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar'
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

const navItems = [
  { label: 'Dashboard', route: 'admin.dashboard', icon: LayoutDashboard, pattern: 'admin.dashboard' },
  { label: 'Data Kecamatan', route: 'admin.kecamatan.index', icon: MapIcon, pattern: 'admin.kecamatan.*' },
  { label: 'Objek Wisata', route: 'admin.objek-wisata.index', icon: Mountain, pattern: 'admin.objek-wisata.*' },
  { label: 'Events', route: 'admin.events.index', icon: Calendar, pattern: 'admin.events.*' },
  { label: 'Fasilitas', route: 'admin.fasilitas-wisata.index', icon: Coffee, pattern: 'admin.fasilitas-wisata.*' },
  { label: 'Moderasi Review', route: 'admin.reviews.index', icon: MessageSquare, pattern: 'admin.reviews.*' },
]

const isUrl = (pattern: string) => {
    // using Inertia's route helper or checking URL
    // @ts-ignore
    return route().current(pattern)
}

const logout = () => {
    router.post('/logout')
}
</script>

<template>
  <div class="flex min-h-screen w-full bg-muted/40">
    <!-- Desktop Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-10 hidden w-64 flex-col border-r bg-background sm:flex">
      <div class="flex h-16 items-center gap-2 border-b px-6">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
          <Mountain class="h-5 w-5" />
        </div>
        <span class="text-lg font-bold tracking-tight">SIG Wisata</span>
      </div>
      
      <nav class="grid gap-1 px-4 py-4">
        <div class="px-2 mb-2 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Main Menu</div>
        
        <Link 
            v-for="item in navItems" 
            :key="item.route" 
            :href="route(item.route)"
            :class="cn(
                'flex items-center gap-3 rounded-lg px-3 py-2 transition-all hover:text-primary',
                isUrl(item.pattern) ? 'bg-muted text-primary' : 'text-muted-foreground'
            )"
        >
            <component :is="item.icon" class="h-4 w-4" />
            {{ item.label }}
        </Link>
      </nav>

      <div class="mt-auto p-4 border-t">
         <div class="flex items-center gap-3 px-2">
            <Avatar>
                <AvatarImage :src="user.avatar_url || ''" />
                <AvatarFallback>{{ user.name.charAt(0) }}</AvatarFallback>
            </Avatar>
            <div class="grid gap-0.5 text-xs">
                <span class="font-semibold">{{ user.name }}</span>
                <span class="text-muted-foreground capitalize">{{ user.role }}</span>
            </div>
         </div>
      </div>
    </aside>

    <div class="flex flex-col sm:gap-4 sm:py-4 sm:pl-64 w-full">
      <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-background px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
        <Sheet>
          <SheetTrigger as-child>
            <Button size="icon" variant="outline" class="sm:hidden">
              <Menu class="h-5 w-5" />
              <span class="sr-only">Toggle Menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="left" class="sm:max-w-xs">
            <SheetHeader class="text-left mb-4">
                <SheetTitle class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <Mountain class="h-5 w-5" />
                    </div>
                    SIG Wisata
                </SheetTitle>
                <SheetDescription>
                    Admin Panel Dashboard
                </SheetDescription>
            </SheetHeader>
            <nav class="grid gap-2 text-lg font-medium">
               <Link 
                    v-for="item in navItems" 
                    :key="item.route" 
                    :href="route(item.route)"
                    :class="cn(
                        'flex items-center gap-4 px-2.5 py-2 hover:text-foreground',
                        isUrl(item.pattern) ? 'text-foreground font-semibold' : 'text-muted-foreground'
                    )"
                >
                    <component :is="item.icon" class="h-5 w-5" />
                    {{ item.label }}
                </Link>
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
            <!-- Flash Messages -->
            <div v-if="$page.props.flash.message" class="rounded-md bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/50 dark:text-green-400">
                {{ $page.props.flash.message }}
            </div>
            <div v-if="$page.props.flash.error" class="rounded-md bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/50 dark:text-red-400">
                {{ $page.props.flash.error }}
            </div>

           <slot />
      </main>
    </div>
  </div>
</template>
