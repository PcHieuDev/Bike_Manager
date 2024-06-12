require('./bootstrap');
import "../css/styleguide.css";
import "../css/app.css";
import '@mdi/font/css/materialdesignicons.css'
import Toast, { POSITION } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import {createApp} from 'vue';
import App from './app.vue';
import store from './store/index.js';
import axios from './axios-interceptor.js';
  
import 'vuetify/lib/styles/main.css'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/lib/components'
import * as directives from 'vuetify/lib/directives'

const vuetify = createVuetify({
    components,
    directives,
});

const options = {
    // Các tùy chọn cấu hình cho toast
    position: "top-right", // Sử dụng chuỗi thay vì thuộc tính POSITION
    timeout: 5000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: false,
  };
import routes from './routes/routes.js'

createApp(App).use(routes).use(vuetify).use(store).use(Toast, options)
.mount('#app')

