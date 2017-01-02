Vue.component('datepicker',require('./components/Datepicker.vue'));

const app = new Vue({
    el: "#app",
    data: {
        date1: new Date(2017,0,2),
        date2: new Date(2017,0,25),

    },
    components: {
    }
});