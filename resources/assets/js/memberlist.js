var members = [];

$.ajax({
    type: 'GET',
    url: "/data/members",
    dataType: 'json',
    success: function(data){
        for (var i = 0; i < data.length; i++){
            members.push(new member(data[i]['id'],data[i]['first_name'],data[i]['last_name'],data[i]['members'],data[i]['guests']));
        }
    },
    error: function(data){
        console.log(data);
    }});

function member(ids, first, last, mem, guests){
    this.id = ids;
    this.first_name = first;
    this.last_name = last;
    this.num_members = mem;
    //Necessary for "cancel"
    this.old_members = mem;
    this.num_guests = guests;

    //DOM Control booleans
    this.editing = false;
    this.has_error = false;

    this.guest_text = function(){
        if (this.num_guests == 0){
            return "No Guests";
        } else if (this.num_guests == 1){
            return "1 Guest";
        } else {
            return this.num_guests + " Guests";
        }
    }

    this.id_url = function(){
        return "/members/" + this.id;
    }

    this.guest_url = function(){
        return this.id_url() + "/guests"
    }

    this.submit = function() {
        var self = this;
        //Prevents submissions when pressing escape
        if (this.canceling){
            this.canceling = false;
            return;
        }
        if (this.old_members == this.num_members){
            this.has_error = false;
            this.editing = false;
            return;
        }
        var input = document.querySelector("tr#member-"+this.id + " td input")
        //Input isn't a number
        if (/[^0-9]+/.test(this.num_members)) {
            $.notifyBar({
                cssClass: "warning",
                html: "Must be a number!"
            });
            this.has_error = true;
            input.select();
            return;
        }

        if (this.num_members === ""){
            $.notifyBar({
                cssClass: "warning",
                html: "Members must not be blank"
            });
            this.has_error = true;
            input.select();
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "/members/" + self.id + '/records',
            dataType: 'json',
            data: {num_members: self.num_members},
            success: function(data){
                self.has_error = false;
                self.editing = false;
                self.old_members = self.num_members;
                return;
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
                        message = data.responseJSON['num_members'][0];
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
                });
                self.has_error = true;
                input.select();
            }
        });

    }
}

function search(){
    if (app.search_query == app.old_search){
        return;
    }
    app.old_search = app.search_query;
    $.ajax({
        type: 'GET',
        url: "/data/members",
        dataType: 'json',
        data: {search_c: app.search_column, search_q: app.search_query},
        success: function(data){
            while(members.length){
                members.pop();
            }
            data.sort(function(a,b){
                return a['last_name'].localeCompare(b['last_name']);
            });
            for (var i = 0; i < data.length; i++){
                members.push(new member(data[i]['id'],data[i]['first_name'],data[i]['last_name'],data[i]['members'],data[i]['guests']));
            }
        },
        error: function(data){
            console.log(data);
        }
    });
}

Vue.component('searchbar', require('./components/SearchBar.vue'));


const app = new Vue({
    el: '#app',
    data: {
        members : members,
        search_query: "",
        old_search: "",
        search_column: "last_name",
        sort_col: "last_name",
        sort_dir: "down",
        data_obj: {
            has_error: false,
            editing: false,
            data: "Test"
        },
        columns: [
            {"type": "btn", "link": "id_url()", "key": "", "icon": "list-alt"},
            {"type": "text", "key": "last_name"},
            {"type": "text", "key": "first_name"},
            {"type": "efield", "key": "num_members"},
            {"type": "btn", "link": "guest_url()", "key": "guest_text()", "icon": ""}
        ]
    },
    methods: {
        search: _.debounce(search,250),
        search_col: function(column){
            if (column != app.search_column){
                app.search_column = column;
                if (app.search_query != ""){
                    //Required to actually perform the search
                    app.old_search = ""
                    search();
                }
            }
        },
        sort: function(column){

            if (column == app.sort_col){
                app.sort_dir = app.sort_dir == 'up' ? 'down' : 'up';
            } else {
                app.sort_col = column;
                app.sort_dir = 'down';
            }
        }
    },
    computed: {
        sorted_members: function(){
            return this.members.sort(function(a,b){
                if (typeof(a[app.sort_col]) == 'string'){
                    return (app.sort_dir=='down' ? 1 : -1) * a[app.sort_col].localeCompare(b[app.sort_col]);
                } else {
                    return (app.sort_dir=='down' ? 1 : -1) * (a[app.sort_col] - (b[app.sort_col]));
                }
            });
        }
    }
})