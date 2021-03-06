function today(){
    var tempDate = new Date();
    return new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate());
}

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
        get_adults(row){
            return this.get_type(row,'adult');
        },
        get_children(row){
            return this.get_type(row,'child');
        },
        get_type(row,type){
            var list = [];
            for (var i = 0; i < row.guests.length; i++){
                if (row.guests[i].type == type){
                    row.guests[i].free_pass = row.guests[i].pivot.free_pass == 1
                    list.push(row.guests[i]);
                }
            }
            return list;
        },
        get_payment(row){
            switch (row.payment_method) {
                case 'account':
                    return "Applied to Account"
                case 'cash':
                    return "Paid Cash"
            }
        },
        get_time(timestring){
            var options = {
                month:'2-digit', day:'2-digit', year: '2-digit', hour: "2-digit", minute: "2-digit"
            };
            var date = new Date(timestring);
            date.setMinutes(date.getMinutes() + (360 - date.getTimezoneOffset()))
            return date.toLocaleTimeString('en-us',options);
        },
        delete_row: function (id) {
            $.confirm({
                body: "Are you sure you want to delete this record? (This cannot be undone!)",
                button: "Delete",
                class: "danger",
                confirm: function(){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: 'POST',
                        url: window.location.href + '/' + id + "/delete",
                        dataType: 'json',
                        data: {},
                        success: function(data){
                            app.get_data();
                        },
                        error: function(data){
                            console.log(data);
                        }
                    });
                }
            })
        },
        receipt: function(id){
            var loc = window.location;
            var baseUrl = loc.protocol + "//" + loc.hostname + (loc.port? ":"+loc.port : "") + "/"
            window.location = baseUrl + 'receipts/' + id;
        },
        get_data: function(){
            this.rows = [
                {
                    'adults' : [{'city': 'Loading...'}],
                    'children' : [{'city': 'Loading...'}],
                    'payment' : 'Loading...',
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
                    var dataRows = data.data;
                    app.rows = [];
                    var newRow = {};
                    for (var i = 0; i < dataRows.length; i++){
                        newRow = {};
                        newRow.adults = app.get_adults(dataRows[i]);
                        newRow.children = app.get_children(dataRows[i]);
                        newRow.cost = dataRows[i].price;
                        newRow.payment = app.get_payment(dataRows[i]);
                        newRow.checkIn = dataRows[i].created_at;
                        newRow.user = dataRows[i].name;
                        newRow.id = dataRows[i].id;
                        app.rows.push(newRow);
                    }
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

