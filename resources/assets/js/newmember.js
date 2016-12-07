Vue.component('multiple-edit',require('./components/MultipleEditBox.vue'))

const app = new Vue ({
    el: "#app",
    data: {
        columns: {
            adults: {keys: ['first_name','last_name'],
                    titles: ['First Name','Last Name']},
            children: {keys: ['first_name','last_name','birth_year'],
                titles: ['First Name','Last Name','Birth Year']},
            phones: {keys: ['num','desc'],
                titles: ['Phone Number','Description']},
            emails: {keys: ['address','desc'],
                titles: ['Email Address','Description']},
        },
        rows: {
            adults: [],
            children: [],
            phones: [],
            emails: [],
        },
        address1: "",
        address2: "",
        city: "",
        state: "",
        zip: "",
    },
    methods: {
        submit: function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data_obj = {
                "address1": this.address1,
                "address2": this.address2,
                "city": this.city,
                "state": this.state,
                "zip": this.zip,
                "adults": this.rows.adults,
                "children:": this.rows.children,
                "phones": this.rows.phones,
                "emails": this.rows.emails
            }
            console.log(data_obj)
            $.ajax({
                type: 'POST',
                url: "/members",
                dataType: 'json',
                data: data_obj,
                success: function(data){
                    console.log(data)
                    window.location.href = "/members/" + data.responseJSON.id;
                },
                error: function(data){
                    console.log(data)
                }
            });
        }
    }
});