Vue.component('paginator',require('./components/Paginator.vue'));

const app = new Vue({
    el: '#app',
    data: {
        sort_col: "name",
        sort_dir: "asc",
        page: 1,
        maxPages: 1,
        rows: [],
        new_pass: "",
        password_error: "",
        user: 0
    },
    methods: {
        get_data: function(){
            $.get({
                url: window.location.href + '/json',
                data: {
                    page: this.page,
                    sort_col: this.sort_col,
                    sort_dir: this.sort_dir
                },
                success: function(data){
                    for (var i = 0; i < data.data.length; i++){
                        data.data[i].admin = data.data[i].admin === 1
                        data.data[i].temp_pass = data.data[i].temp_pass === 1
                        data.data[i].disabled = data.data[i].disabled === 1
                    }
                    app.rows = data.data;
                    app.maxPages = data.last_page;
                }
            });
        },
        sort: function(column){
            if (this.sort_col == column){
                this.sort_dir = this.sort_dir == "asc" ? "desc" : "asc";
            } else {
                this.sort_col = column;
                this.sort_dir = "asc";
            }
            this.get_data();
        },
        update_flags: function(id, index){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var loc = window.location;
            var baseUrl = loc.protocol + "//" + loc.hostname + (loc.port? ":"+loc.port : "") + "/"

            $.ajax({
                type: 'POST',
                url: baseUrl + "users/" + id + "/flags",
                dataType: 'json',
                data: {
                    admin: app.rows[index].admin,
                    temp_pass: app.rows[index].temp_pass,
                    disabled: app.rows[index].disabled,
                },
                success: function(data){
                    app.get_data();
                },
                error: function(data){
                    console.log(data);
                }
            });

        },
        delete_user: function(id){
            if (!confirm("Are you sure? (This cannot be undone!)")){
                return;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var loc = window.location;
            var baseUrl = loc.protocol + "//" + loc.hostname + (loc.port? ":"+loc.port : "") + "/"

            $.ajax({
                type: 'POST',
                url: baseUrl + "users/" + id + "/delete",
                dataType: 'json',
                success: function(data){
                    app.get_data();
                },
                error: function(data){
                    console.log(data);
                }
            });
        },
        set_password: function(){
            if (!this.new_pass){
                this.password_error = "Password is required";
                return;
            }
            if (this.new_pass.length < 8){
                this.password_error = "Password must be at least 8 Characters";
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var loc = window.location;
            var baseUrl = loc.protocol + "//" + loc.hostname + (loc.port? ":"+loc.port : "") + "/"

            $.ajax({
                type: 'POST',
                url: baseUrl + "users/" + app.user + "/password",
                dataType: 'json',
                data: {password: app.new_pass},
                success: function(data){
                    app.new_pass = "";
                    $("#passwordModal").modal("hide");
                },
                error: function(data){
                    console.log(data);
                }
            });

        }
    },
    created: function(){
        this.get_data();
    }
});