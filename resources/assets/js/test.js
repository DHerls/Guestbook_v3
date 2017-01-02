Vue.component('paginator',require('./components/Paginator.vue'));

const app = new Vue({
    el: "#app",
    data: {
        lastPage: 20,
        currentPage: 19,
    },
    components: {
    }
});