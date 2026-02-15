<template>
  <div class="flex items-center justify-between space-y-2 mb-6">
    <div>
      <h2 class="text-2xl font-bold tracking-tight"><slot name="title" /></h2>
      <p class="text-muted-foreground"><slot name="description" /></p>
    </div>
    <div class="flex items-center space-x-2">
      <slot name="actions" />
    </div>
  </div>
</template>
