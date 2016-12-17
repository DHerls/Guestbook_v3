Vue.component('multiple-display',require('./components/MultipleDisplayEditBox.vue'));
var memberFields = require('./json/memberFields.json');

const app = new Vue({
    el: "#app",
    data: {
        info: memberFields
    },
    methods: {

    },
    created: function() {
        $.get({
            url: window.location.href,
            success: function(data){
                console.log(data);
            }
        });
    }
});