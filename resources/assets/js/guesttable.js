function today(){
    var tempDate = new Date();
    return new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate());
}

Vue.component('datepicker',require('./components/Datepicker.vue'));

const app = new Vue({
    el: '#app',
    data: {
        sort_col: "checkIn",
        sort_dir: "down",
        date1: today(),
        date2: today(),
        rows: [],
    },
    methods: {
        sort: function(column){
            if (this.sort_col == column){
                this.sort_dir = this.sort_dir == "up" ? "down" : "up";
            } else {
                this.sort_col = column;
                this.sort_dir = "up";
            }
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
                case 'pass':
                    return "Free Pass"
            }
        },
        get_time(timestring){
            var options = {
                month:'2-digit', day:'2-digit', year: '2-digit', hour: "2-digit", minute: "2-digit"
            };
            var date = new Date(timestring);
            return date.toLocaleTimeString('en-us',options);
        },
        getData: function(){
            $.get({
                url: window.location.href + "/json",
                data: {
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
                    app.rows = [];
                    var newRow = {};
                    for (var i = 0; i < data.length; i++){
                        newRow = {};
                        newRow.adults = app.get_adults(data[i]);
                        newRow.children = app.get_children(data[i]);
                        newRow.cost = data[i].price;
                        newRow.payment = app.get_payment(data[i]);
                        newRow.checkIn = data[i].created_at;
                        app.rows.push(newRow);
                    }
                }
            });
        }
    },
    computed: {
        sorted_rows: function() {
            if (this.rows.length == 0){
                return []
            }
            if (typeof(this.rows[0][this.sort_col]) == 'string'){
                this.rows.sort(function(a,b){
                    return (app.sort_dir=="down" ? -1 : 1) * a[app.sort_col].localeCompare(b[app.sort_col]);
                });
            } else {
                this.rows.sort(function(a,b){
                    return (app.sort_dir=="down" ? -1 : 1) * (a[app.sort_col] - b[app.sort_col]);
                });
            }

            return this.rows;
        }
    },
    watch:{
        date1: function (val) {
            this.getData();
        },
        date2: function (val) {
            this.getData();
        }
    },
    created: function () {
        this.getData();
    }

});

