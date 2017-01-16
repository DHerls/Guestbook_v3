Vue.component('searchbar', require('./components/SearchBar.vue'));
Vue.component('editfield', require('./components/EditField.vue'));
Vue.component('paginator', require('./components/Paginator.vue'))

import {validator} from "./validator";

const app = new Vue({
    el: '#app',
    data: {
        search_string: "",
        search_col: "last_names",
        sort_col: "last_names",
        sort_dir: "asc",
        rows: [],
        currentMember: {},
        page: 1,
        max_pages: 1,
        balance: {
            amount: 0,
            reason: "",
            amount_error: "",
            reason_error: ""
        },
        note: {
            text: "",
            error: ""
        }
    },
    methods: {
        guest_string: function(num){
            if (num == 0){
                return "No Guests"
            } else if (num == 1){
                return "1 Guest"
            } else {
                return num + " Guests";
            }
        },
        set_sort_col: function(column){
            if (this.sort_col == column){
                this.sort_dir = this.sort_dir == "asc" ? "desc" : "asc";
            } else {
                this.sort_col = column;
                this.sort_dir = "asc";
            }
            this.search();
        },

        search: function(reset = true){
            var app = this;
            if (reset){
                this.page = 1;
            }
            $.ajax({
                type: 'GET',
                url: '/members/json',
                dataType: 'json',
                data: {
                    page: app.page,
                    search_col: app.search_col,
                    search_q: app.search_string,
                    sort_col: app.sort_col,
                    sort_dir: app.sort_dir
                },
                success: function(data){
                    app.max_pages = data.last_page;
                    for (var i = 0; i < data.data.length; i++){
                        data.data[i].balance = parseFloat(data.data[i].balance);
                        data.data[i].guests = parseFloat(data.data[i].guests);
                    }
                    app.rows = data.data;
                },
                error: function(data){
                    console.log(data);
                }
            });
        },

        set_member: function(data){
            this.currentMember = data;
        },
        addNote: function(){
            this.note.error = validator.validate(this.note.text,"required|string|max:255","Text");
            if (this.note.error){
                return;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: window.location.href + "/members/" + app.currentMember.id + "/notes",
                dataType: 'json',
                data: {note: app.note.text},
                success: function(data){
                    app.currentMember.note = app.note.text;
                    app.note = {
                        text: "",
                        error: ""
                    };
                    $("#noteModal").modal("hide");
                },
                error: function(data){
                    console.log(data);
                }
            });
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
    created: function () {
        this.search();
    },
    watch: {
        page: function () {
            this.search(false);
        },
    }

});

