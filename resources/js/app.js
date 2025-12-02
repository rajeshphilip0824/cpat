import { createApp, h } from 'vue'
import { createInertiaApp } from "@inertiajs/vue3";
import { route } from 'ziggy-js'
import '../css/app.css'

const pages = import.meta.glob('./Pages/**/*.vue')

/* createInertiaApp({
  resolve: name => pages[`./Pages/${name}.vue`](),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    app.use(plugin)

    app.config.globalProperties.route = (...args) =>
        route(...args, window.Ziggy)

    app.mount(el)
  },
}) */
createInertiaApp({
    resolve: name => require(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});