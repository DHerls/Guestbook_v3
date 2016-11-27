Vue.component('multiple-edit',require('./components/MultipleEditBox.vue'))

const app = new Vue ({
    el: "#app",
    data: {
        columns: {
            adults: {keys: ['first_name','last_name'],
                    titles: ['First Name','Last Name']}
        }

    },
    methods: {

    }
});