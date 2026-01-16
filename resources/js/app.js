import './bootstrap.js';             
import { createApp } from 'vue';     
import Items from './components/Items.vue'; 

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

createApp(Items).mount('#app');

console.log("Vue 3 app mounted to #app");
