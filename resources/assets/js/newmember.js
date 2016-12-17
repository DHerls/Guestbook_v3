import {validator} from "./validator";
var memberFields = require('./json/memberFields.json');

Vue.component('multiple-edit',require('./components/MultipleEditBox.vue'));

const app = new Vue ({
    el: "#app",
    data: {
        info: memberFields,
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
                            var error = validator.validate(row[column.key],column.validation,column.title);
                            if (error){
                                is_validated = false;
                                row.errors[column.key] = error;
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