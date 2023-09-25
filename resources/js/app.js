require('./bootstrap');
import { createApp } from 'vue'
import App from './components/App.vue'
import router from "./router"
import ViewUIPlus from 'view-ui-plus'
import 'view-ui-plus/dist/styles/viewuiplus.css'
import locale from 'view-ui-plus/dist/locale/ru-RU';
import moment from 'moment'
import { vMaska } from "maska"

import VueEasyLightbox from 'vue-easy-lightbox'
import 'vue-easy-lightbox/external-css/vue-easy-lightbox.css'


import common from "./common.js"

const app = createApp({mainapp: App})

app.directive("maska", vMaska)
app.component('mainapp', App)
app.use(router)
    .use(ViewUIPlus, {locale})
    .use(moment)
    .use(VueEasyLightbox)
    .mixin(common)
    .mount('#app')
