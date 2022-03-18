require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
// window.Vue = require('vue').default;

Vue.use(VueRouter)

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
