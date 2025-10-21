import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'

// Automatically import all Vue pages
const pages = import.meta.glob('./Pages/**/*.vue')

createInertiaApp({
  resolve: name => pages[`./Pages/${name}.vue`](), // ✅ dynamic import
  setup({ el, app, props, plugin }) {
    createApp({ render: () => h(app, props) })
      .use(plugin)
      .mount(el)
  },
})
