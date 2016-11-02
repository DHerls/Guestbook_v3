
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./jquery.notifyBar');
require('./tablesorter');
//require('./memberlist');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var members = [];

$.ajax({
    type: 'GET',
    url: "/data/members",
    dataType: 'json',
    success: function(data){
        data.sort(function(a,b){
            return a['last_name'].localeCompare(b['last_name']);
        });
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

    this.edit = function() {
        this.editing = true;
        this.old_members = this.num_members;
        Vue.nextTick((function() {
            document.querySelector("tr#member-"+this.id + " td input").select();
        }).bind(this));
    }

    this.cancel = function() {
        this.canceling = true;
        this.num_members = this.old_members;
        this.editing = false;
        this.has_error = false;
    }

    this.submit = function() {
        var self = this;
        //Prevents submissions when pressing escape
        if (this.canceling){
            this.canceling = false;
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
        data: {search_c: "last_name", search_q: app.search_query},
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

const app = new Vue({
    el: '#app',
    data: {
        members : members,
        search_query: "",
        old_search: ""
    },
    methods: {
        search: _.debounce(search,250)
    }
});