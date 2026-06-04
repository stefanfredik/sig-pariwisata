<template>
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-full h-full" fill="none">
    <defs>
      <mask id="parking-mask">
        <!-- Background of mask (white = keep) -->
        <rect x="0" y="0" width="24" height="24" fill="white" />
        
        <!-- P letter (black = cutout) -->
        <text x="12" y="11" font-family="'Inter', 'Montserrat', 'Outfit', sans-serif" font-weight="900" font-size="9.5" text-anchor="middle" fill="black">P</text>
        
        <!-- Divider line (black = cutout) -->
        <line x1="2" y1="14" x2="22" y2="14" stroke="black" stroke-width="1.2" />
        
        <!-- PARKIR text (black = cutout) -->
        <text x="12" y="20.5" font-family="'Inter', 'Montserrat', 'Outfit', sans-serif" font-weight="900" font-size="3.6" text-anchor="middle" letter-spacing="0.4" fill="black">PARKIR</text>
      </mask>
    </defs>
    
    <!-- The shape filled with currentColor (which changes dynamically with hover classes) -->
    <rect x="0.5" y="0.5" width="23" height="23" rx="4.5" fill="currentColor" mask="url(#parking-mask)" />
  </svg>
</template>
