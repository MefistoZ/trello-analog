require('./bootstrap');

import Vue from 'vue'

Vue.component('app', require('./components/index').default)

const app = new Vue({
    el: '#app'
})
