function today(){
    var tempDate = new Date();
    return new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate());
}

import {validator} from "../validator";

Vue.component('datepicker',require('./../components/Datepicker.vue'));
Vue.component('paginator',require('./../components/Paginator.vue'));

const app = new Vue({
    el: '#app',
    data: {
        sort_col: "created_at",
        sort_dir: "desc",
        date1: today(),
        date2: today(),
        page: 1,
        maxPages: 1,
        rows: [],
        current_balance: 0,
        balance: {
            amount: 0,
            reason: "",
            amount_error: "",
            reason_error: ""
        }
    },
    methods: {
        sort: function(column){
            if (this.sort_col == column){
                this.sort_dir = this.sort_dir == "asc" ? "desc" : "asc";
            } else {
                this.sort_col = column;
                this.sort_dir = "asc";
            }
            this.get_data();
        },
        charge: function () {
            var app = this;

            this.balance.amount_error = validator.validate(this.balance.amount,"required|numeric","Amount");
            this.balance.reason_error = validator.validate(this.balance.reason,"required|string|max:45","Reason");
            if (this.balance.amount_error || this.balance.reason_error) {
                return;
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
                data: {amount: app.balance.amount, reason: app.balance.reason},
                success: function(data){
                    app.current_balance = app.current_balance + app.balance.amount;
                    app.balance = {
                        amount: 0,
                        reason: "",
                        amount_error: "",
                        reason_error: ""
                    };
                    app.get_data();
                    $("#balanceModal").modal("hide");
                },
                error: function(data){
                    console.log(data);
                }
            });

        },
        get_time(timestring){
            var options = {
                month:'2-digit', day:'2-digit', year: '2-digit', hour: "2-digit", minute: "2-digit"
            };
            var date = new Date(timestring);
            return date.toLocaleTimeString('en-us',options);
        },
        get_data: function () {
            this.rows = [
                {
                    'name' : 'Loading...',
                    'change_amount': 'Loading...',
                    'reason': 'Loading...',
                }
            ]
            $.get({
                url: window.location.href + "/json",
                data: {
                    page: this.page,
                    sort_col: this.sort_col,
                    sort_dir: this.sort_dir,
                    start: {
                        year: this.date1.getFullYear(),
                        month: this.date1.getMonth()+1,
                        date: this.date1.getDate()
                    },
                    end: {
                        year: this.date2.getFullYear(),
                        month: this.date2.getMonth()+1,
                        date: this.date2.getDate()
                    }
                },
                success: function(data){
                    app.maxPages = data.last_page;
                    app.current_balance = data.current_balance;
                    app.rows = data.data;
                }
            });
        }
    },
    watch:{
        date1: function (val) {
            this.get_data();
        },
        date2: function (val) {
            this.get_data();
        },
        page: function (val) {
            this.get_data();
        }
    },
    created: function () {
        this.get_data();
    }
});