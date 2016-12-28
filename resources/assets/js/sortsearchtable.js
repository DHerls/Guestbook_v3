Vue.component('searchbar', require('./components/SearchBar.vue'));
Vue.component('editfield', require('./components/EditField.vue'));

import {validator} from "./validator";

const app = new Vue({
    el: '#app',
    data: {
        sort_col: "",
        sort_dir: "up",
        data: [],
        data_url: "",
        currentMember: {},
        balance: {
            amount: 0,
            reason: "",
            amount_error: "",
            reason_error: ""
        }
    },
    methods: {
        set_sort_col: function(column){
            if (this.sort_col == column){
                this.sort_dir = this.sort_dir == "up" ? "down" : "up";
            } else {
                this.sort_col = column;
                this.sort_dir = "down";
            }
        },

        search: function(text = "", column = this.sort_col){
            $.ajax({
                type: 'GET',
                url: app.data_url,
                dataType: 'json',
                data: {search_c: column, search_q: text},
                success: function(data){
                    while(app.data.length){
                        app.data.pop();
                    }
                    while (data.length){
                        app.data.push(data.pop());
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        },

        set_member: function(data){
            this.currentMember = data;
        },

        charge: function () {
            this.balance.amount_error = validator.validate(this.balance.amount,"required|numeric","Amount");
            this.balance.reason_error = validator.validate(this.balance.reason,"required|string|max:45","Reason");
            if (!this.balance.amount_error && !this.balance.reason_error){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: window.location.href + "/members/" + app.currentMember.id + "/balance",
                    dataType: 'json',
                    data: {amount: app.balance.amount, reason: app.balance.reason},
                    success: function(data){
                        app.currentMember.balance = app.currentMember.balance + app.balance.amount;
                        app.balance = {
                            amount: 0,
                            reason: "",
                            amount_error: "",
                            reason_error: ""
                        };
                        $("#balanceModal").modal("hide");
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            }
        },

        submit: function(data_obj, url, callback){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: data_obj,
                success: function(data){
                    callback(true);
                },
                error: function(data){
                    var message = "Generic Error";
                    var cl = "error";
                    switch (data.status){
                        case 404:
                            message = "Cannot connect to server";
                            cl = "error";
                            break;
                        case 422:
                            message = data.responseJSON['members'][0];
                            cl = "warning";
                            break;
                        case 500:
                            message = "Internal Server Error";
                            cl = "error";
                            console.log(data);
                        default:

                    }
                    $.notifyBar({
                        cssClass: cl,
                        html: message
                    })

                    callback(false);
                }
            });
        }
    },
    computed: {
        sorted_data: function() {
            if (this.data.length == 0){
                return []
            }
            if (typeof(this.data[0][this.sort_col]) == 'string'){
                this.data.sort(function(a,b){
                    return (app.sort_dir=="down" ? -1 : 1) * a[app.sort_col].localeCompare(b[app.sort_col]);
                });
            } else {
                this.data.sort(function(a,b){
                    return (app.sort_dir=="down" ? -1 : 1) * (a[app.sort_col] - b[app.sort_col]);
                });
            }

            return this.data;
        }
    },
    created: function () {
        this.data_url = document.head.querySelector("[name=data-url]").content;
        this.sort_col = document.head.querySelector("[name=sort-col]").content;
        Vue.nextTick(this.search);
    }

});

