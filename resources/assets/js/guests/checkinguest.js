import {validator} from "../validator";
let SignaturePad = require("signature_pad");

var guestFields = require('../json/guestFields.json');

Vue.component('multiple-edit',require('../components/MultipleEditBox.vue'));
Vue.component('vue-radio',require('../components/VueRadio.vue'));

Vue.config.ignoredElements = ['canvas']
const app = new Vue ({
    el: "#app",
    data: {
        info: guestFields,
        payment: "account",
        paymentMethods: {
            account: "Apply to Account",
            cash: "Paid in Cash",
        },
        mSigError: false,
        gSigError: false,
        visits: [],
        username: "",
        password: "",
        login_error: "",
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
                if (column != 'errors' && column != 'pass' && row[column]){
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

            if (memberSig.isEmpty()){
                this.mSigError = true;
                validated = false;
            } else {
                this.mSigError = false;
            }

            if (guestSig.isEmpty()){
                this.gSigError = true;
                validated = false;
            } else {
                this.gSigError = false;
            }

            return validated;
        },
        hide_override: function () {
            $('#overrideModal').modal('hide');
        },
        hide_admin: function () {
            $('#adminModal').modal('hide');
        },
        override: function(){
            this.submit(true);
            this.username = "";
            this.password = "";
        },
        request_override() {
            $('#adminModal').modal('show');
        },
        submit: function(override = false){
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
            data_obj.member_sig = memberSig.toDataURL();
            data_obj.guest_sig = guestSig.toDataURL();
            if (override){
                data_obj.override = 1;
                data_obj.username = this.username;
                data_obj.password = this.password;
            } else {
                data_obj.override = 0;
            }

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
                    if (data.status == 403){
                        app.visits = data.responseJSON;
                        $('#overrideModal').modal('show');
                    } else if (data.status == 401 && override) {
                        app.login_error = "The Username or Password is Incorrect";
                    } else {
                        console.log(data);
                    }

                }
            });

        }
    },
    computed: {
        cost: function() {
            var cost = 0;
            var today = new Date();
            var isWeekend = today.getDay() == 0 || today.getDay() == 6;
            for (var title in this.info){
                for(var i = 0; i < this.info[title].rows.length; i++){
                    if (!this.rowIsEmpty(this.info[title].rows[i])){
                        if (!this.info[title].rows[i].pass){
                            cost += this.info[title].price[isWeekend ? 'weekend' : 'weekday'];
                        }
                    }
                }
            }

            return cost;
        }
    }
});

var memberCanvas = document.getElementById("memberSig");
memberCanvas.width = memberCanvas.offsetWidth;
memberCanvas.height = memberCanvas.offsetWidth * 35 / 110;

var guestCanvas = document.getElementById("guestSig");
guestCanvas.width = guestCanvas.offsetWidth;
guestCanvas.height = guestCanvas.offsetWidth * 35 / 110;


var memberSig = new SignaturePad(memberCanvas);
var guestSig = new SignaturePad(guestCanvas);

window.clear_sig = function(type) {
    if (type === 'guest'){
        guestSig.clear();
    } else if (type === 'member'){
        memberSig.clear();
    }
};
