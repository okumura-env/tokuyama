import './bootstrap';
import router from './routes';
import { createApp } from 'vue/dist/vue.esm-bundler';
import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import { aliases, mdi } from 'vuetify/iconsets/mdi'; 

const vuetify = createVuetify({
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
          mdi,
        },
      },
    theme: {
      defaultTheme: 'light',
      themes: {
        light: {
          colors: {
            primary: '#1A3A86',
            'on-primary': '#FFFFFF', // ヘッダーやサイドバーの文字色
            secondary: '#C5C7CB',
          },
        },
        dark: {
          colors: {
            primary: '#1A3A86',
            'on-primary': '#FFFFFF', // ヘッダーやサイドバーの文字色
            secondary: '#C5C7CB',
          },
        },
      },
    },
  });

const app = createApp({})
app.use(vuetify)
app.use(router)
app.mount('#app');