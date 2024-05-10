require('./bootstrap');
import "../css/styleguide.css";
import "../css/app.css";
import '@mdi/font/css/materialdesignicons.css'

import {createApp} from 'vue';
import App from './app.vue';

import 'vuetify/lib/styles/main.css'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/lib/components'
import * as directives from 'vuetify/lib/directives'


const vuetify = createVuetify({
    components,
    directives,
});
import routes from './routes/routes.js'

createApp(App).use(routes).use(vuetify).mount('#app')

