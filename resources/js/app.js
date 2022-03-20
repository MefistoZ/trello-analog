require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuelidate from 'vuelidate'
import PortalVue from 'portal-vue'
import { BootstrapVue, IconsPlugin, ModalPlugin } from 'bootstrap-vue'

import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(VueRouter)
Vue.use(Vuelidate)
Vue.use(PortalVue)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(ModalPlugin)

import Home from "./components/Home";
import Desks from "./components/desks/Desks";
import ShowDesk from "./components/desks/ShowDesk";

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/desks',
        name: 'desks',
        component: Desks
    },
    {
        path: '/desks/:deskId',
        name: 'ShowDesk',
        component: ShowDesk,
        props: true
    }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

Vue.component('app', require('./components/index').default)

const app = new Vue({
    el: '#app',
    router
})
