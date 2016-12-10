Vue.component('multiple-edit',require('./components/MultipleEditBox.vue'));

var email_re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;

const app = new Vue ({
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

        },
        address: [
            {key: "address1", title: "Address Line 1", required: true, width: 3, error: ''},
            {key: "address2", title: "Address Line 2", required: false, width: 3, error: ''},
            {key: "city", title: "City", required: true, width: 2, error: ''},
            {key: "state", title: "State", required: true, width: 2, error: ''},
            {key: "zip", title: "Zip Code", required: true, width: 2, error: ''}
        ]
        ,

    },
    methods: {
        remove_blanks: function() {
            var to_remove;
            var box;
            for (var box_name in this.info){
                box = this.info[box_name];
                to_remove = [];
                for (var i_row = 0; i_row < box.rows.length; i_row++){
                    if (i_row != 0 && this.rowIsEmpty(box.rows[i_row])){
                        to_remove.push(i_row);
                    }
                }
                for (var i_row = 0; i_row < to_remove.length; i_row++){
                    box.rows.splice(to_remove[i_row]-i_row,1);
                }
            }
        },
        validate: function(){
            this.remove_blanks();

            var is_validated = true;

            var box;
            var row;
            var column;
            for (var box_name in this.info) {
                box = this.info[box_name];
                for (var i_row = 0; i_row < box.rows.length; i_row++) {
                    row = box.rows[i_row];
                    row.errors = {};

                    //Make sure the form is required or there is data to validate
                    if (box.required || !this.rowIsEmpty(row)) {
                        for (var i_col = 0; i_col < box.columns.length; i_col++) {
                            column = box.columns[i_col];

                            //Check for required fields
                            if (column.required && !row[column.key]) {
                                row.errors[column.key] = column.title + " is required";
                                is_validated = false;
                            }

                            //Check for field types
                            var error = "";
                            switch(column.type){
                                case "string":
                                    if (typeof row[column.key] != 'string'){
                                        error = column.title + " must be a string."
                                    }
                                    break;
                                case "num":
                                    if (isNaN(row[column.key])){
                                        error = column.title + " must be a number."
                                    }
                                    break;
                                case "email":
                                    if (!email_re.test(row[column.key])){
                                        error = column.title + " must be a valid email address."
                                    }
                                    break;
                            }
                            if (error){
                                row.errors[column.key] = error;
                                is_validated = false;
                            }
                        }
                    }
                }
            }
            //Special logic for fucking addresses
            for (var i = 0; i < this.address.length; i++){
                this.address[i].error = "";
                if (this.address[i].required && !this.address[i].value){
                    this.address[i].error = this.address[i].title + " is required";
                    is_validated = false;
                }
            }

            return is_validated;
        },
        rowIsEmpty: function(row){
            for (var column in row){
                if (column != 'errors' && row[column]){
                    return false;
                }
            }
            return true;

        },
        boxIsEmpty: function(box){
            if (box.rows.length > 1){
                return false;
            }
            if (!this.rowIsEmpty(box.rows[0])){
                return false;
            }
            return true;
        },
        submit: function() {
            if (!this.validate()){
                return;
            }

            var data_obj = {};

            for (var key in this.info){
                if (this.info[key].required || !this.boxIsEmpty(this.info[key])){
                    data_obj[key] = this.info[key].rows;
                }
            }

            for (var i = 0; i < this.address.length; i++){
                data_obj[this.address[i].key] = this.address[i].value;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            console.log(data_obj)
            $.ajax({
                type: 'POST',
                url: "/members",
                dataType: 'json',
                data: data_obj,
                success: function(data){
                    window.location.href = "/members/" + data.id;
                },
                error: function(data){
                    console.log(data)
                }
            });
        }
    }
});