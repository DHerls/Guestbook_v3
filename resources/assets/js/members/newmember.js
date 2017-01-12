import {validator} from "../validator";
var memberFields = require('./../json/memberFields.json');
var addressFields = require('./../json/addressFields.json');

Vue.component('multiple-edit',require('./../components/MultipleEditBox.vue'));

const app = new Vue ({
    el: "#app",
    data: {
        info: memberFields,
        address: addressFields
    },
    methods: {
        remove_blanks: function(box) {
            var to_remove;
            to_remove = [];
            for (var i_row = 0; i_row < box.rows.length; i_row++){
                if (i_row != 0 && this.rowIsEmpty(box.rows[i_row])){
                    to_remove.push(i_row);
                }
            }
            for (var i_row = 0; i_row < to_remove.length; i_row++){
                box.rows.splice(to_remove[i_row]-i_row,1);
            }
        },
        validate: function(box){
            this.remove_blanks(box);

            var is_validated = true;

            var row;
            var column;
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
            var validated = true;
            for (var box_name in this.info) {
                if (!this.validate(this.info[box_name])) {
                    validated = false;
                }
            }
            if (!this.validate(this.address)) {
                validated = false;
            }

            if (!validated){
                return;
            }

            var data_obj = {};

            for (var key in this.info){
                if (this.info[key].required || !this.boxIsEmpty(this.info[key])){
                    data_obj[key] = this.info[key].rows;
                }
            }

            for (var i = 0; i < this.address.columns.length; i++){
                data_obj[this.address.columns[i].key] = this.address.rows[0][this.address.columns[i].key];
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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