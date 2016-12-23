import {validator} from "./validator";
var guestFields = require('./json/guestFields.json');

Vue.component('multiple-edit',require('./components/MultipleEditBox.vue'));
Vue.component('vue-radio',require('./components/VueRadio.vue'));

const app = new Vue ({
    el: "#app",
    data: {
        info: guestFields,
        payment: "account",
        paymentMethods: {
            account: "Apply to Account",
            cash: "Paid in Cash",
            pass: "Free Pass",
        }
    },
    methods: {
        filledRows: function (box) {
            var count = 0;
            for (var i = 0; i < box.rows.length; i++){
                if (!this.rowIsEmpty(box.rows[i])){
                    count++;
                }
            }
            return count;
        },
        rowIsEmpty: function(row){
            for (var column in row){
                if (column != 'errors' && row[column]){
                    return false;
                }
            }
            return true;

        },
        setPayment: function(payment){
            this.payment = payment;
        },
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
        validate_box: function(box){
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
        validate: function(){
            var validated = true;

            //Make sure at least one guest
            var records = 0;
            for (var title in this.info){
                records += this.filledRows(this.info[title]);
            }
            if (records == 0){
                $.notifyBar({
                    cssClass: 'warning',
                    html: 'At least one guest is required'
                })
                validated = false;
            }

            //Validate inputs
            for (var title in this.info){
                if (!this.validate_box(this.info[title])){
                    validated = false;
                }
            }

            return validated;
        },
        submit: function(){
            if (!this.validate()){
                return;
            }

            var data_obj = {};
            var newRow = {};
            var row;
            var col;
            for (var title in this.info){
                if (!(this.filledRows(this.info[title]) === 0)) {
                    data_obj[title] = [];
                    for (var i_row = 0; i_row < this.info[title].rows.length; i_row++){
                        row = this.info[title].rows[i_row];
                        newRow = {};
                        for (var i_col = 0; i_col < this.info[title].columns.length; i_col++){
                            col = this.info[title].columns[i_col];
                            newRow[col.key] = row[col.key];
                        }
                        data_obj[title].push(newRow);
                    }
                }
            }
            data_obj.payment = this.payment;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: window.location.href,
                dataType: 'json',
                data: data_obj,
                success: function(data){
                    window.location.href = window.location.href.substring(0,window.location.href.search('/guests')+7);
                },
                error: function(data){
                    for (var title in app.info){
                        app.info[title].visits = [];
                    }
                    if (data.status == 403){
                        for (var i = 0; i < data.responseJSON.length; i++){
                            var guest = data.responseJSON[i];
                            if (guest.type == 'adult'){
                                app.info['adults'].visits.push(guest);
                            } else {
                                app.info['children'].visits.push(guest);
                            }
                        }
                    } else {
                        console.log(data);
                    }
                }
            });

        }
    },
    computed: {
        cost: function() {
            if (this.payment == 'pass'){
                return 0;
            }
            var cost = 0;
            var today = new Date();
            var isWeekend = today.getDay() == 0 || today.getDay() == 6;
            for (var title in this.info){
                if (isWeekend){
                    cost += this.info[title].price['weekend'] * this.filledRows(this.info[title]);
                } else {
                    cost += this.info[title].price['weekday'] * this.filledRows(this.info[title]);
                }
            }

            return cost;
        }
    }
});