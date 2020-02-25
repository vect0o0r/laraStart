/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



require('./bootstrap');

window.Vue = require('vue');
import moment from "moment";
import VueProgressBar from 'vue-progressbar'
import Swal from 'sweetalert2'
window.Swal = Swal;

import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
import VueRouter from 'vue-router'

Vue.use(VueRouter);
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

import ProfileComponent from './components/ProfileComponent';
import DashboardComponent from './components/DashboardComponent';
import UsersComponent from './components/UsersComponent';
import DeveloperComponent from './components/DeveloperComponent';

// Vue.component('profile-component', require().default);
// Vue.component('dashboard-component', require().default);
let routes = [
    { path: '/dashboard', component:DashboardComponent },
    { path: '/profile', component:ProfileComponent },
    { path: '/users', component:UsersComponent },
    { path: '/developer', component:DeveloperComponent }
];

const router = new VueRouter({
    mode:'history',
    routes // short for `routes: routes`
});
Vue.filter('upperText',function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
});
Vue.filter('onlyDate',function (text) {
    return moment(text).format('MMMM Do YYYY, ')
});

window.Fire = new Vue();
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
window.Toast = Toast;
const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'left',
    inverse: false
};

Vue.use(VueProgressBar, options);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
//     router
// });
const app = new Vue({
    router,
}).$mount('#app');
