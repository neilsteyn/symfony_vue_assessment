import Vue from 'vue';
import axios from 'axios'
import VueAxios from 'vue-axios'

import ContactForm from './components/ContactForm'
import LoginForm from './components/LoginForm'

Vue.use(VueAxios, axios)

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#app',
    components: {
        ContactForm,
        LoginForm
    }
});