import { route as ziggyRoute } from 'ziggy-js'
import { PageProps as InertiaPageProps } from '@inertiajs/core'

declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof ziggyRoute;
  }
}

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps, Record<string, unknown> {}
}
