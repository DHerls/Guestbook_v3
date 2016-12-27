const app = new Vue({
    el: '#app',
    data: {
        sort_col: "checkIn",
        sort_dir: "down",
        rows: [],
    },
    methods: {
        sort: function(column){
            if (this.sort_col == column){
                this.sort_dir = this.sort_dir == "up" ? "down" : "up";
            } else {
                this.sort_col = column;
                this.sort_dir = "down";
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
                hour: "2-digit", minute: "2-digit"
            };
            var date = new Date(timestring);
            return date.toLocaleTimeString('en-us',options);
        }
    },
    computed: {
        sorted_rows: function() {
            if (this.rows.length == 0){
                return []
            }
            if (typeof(this.rows[0][this.sort_col]) == 'string'){
                this.rows.sort(function(a,b){
                    return (app.sort_dir=="up" ? -1 : 1) * a[app.sort_col].localeCompare(b[app.sort_col]);
                });
            } else {
                this.rows.sort(function(a,b){
                    return (app.sort_dir=="up" ? -1 : 1) * (a[app.sort_col] - b[app.sort_col]);
                });
            }

            return this.rows;
        }
    },
    created: function () {
        $.get({
            url: window.location.href + "/json",
            success: function(data){
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

});

