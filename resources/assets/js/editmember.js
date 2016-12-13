Vue.component('multiple-display',require('./components/MultipleDisplayEditBox.vue'));

const app = new Vue({
    el: "#app",
    data: {
        info: {
            adults: {
                columns:[
                    {key: 'first_name', title: "First Name", required: true, type: 'string'},
                    {key: 'last_name', title: "Last Name", required: true, type: 'string'}
                ],
                rows: [],
                title: "Adults",
                required: true
            },
            children: {
                columns:[
                    {key: 'first_name', title: "First Name", required: true, type: 'string'},
                    {key: 'last_name', title: "Last Name", required: true, type: 'string'},
                    {key: 'birth_year', title: "Birth Year", required: false, type: 'num'}
                ],
                title: "Children",
                rows: []
            },
            phones: {
                columns:[
                    {key: 'number', title: "Phone Number", required: true, type: 'num'},
                    {key: 'description', title: "Description", required: false, type: 'string'}
                ],
                title: "Phone Numbers",
                rows: []
            },
            emails: {
                columns:[
                    {key: 'address', title: "Email Address", required: true, type: 'email'},
                    {key: 'description', title: "Description", required: false, type: 'string'}
                ],
                rows: [],
                title: "Email Addresses"
            },
        }
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