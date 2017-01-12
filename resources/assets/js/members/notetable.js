Vue.component('paginator',require('./../components/Paginator.vue'));

const app = new Vue({
    el: '#app',
    data: {
        sort_col: "created_at",
        sort_dir: "desc",
        page: 1,
        maxPages: 1,
        rows: [],
        note: {
            text: "",
            error: ""
        },
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
        get_time(timestring){
            var options = {
                month:'2-digit', day:'2-digit', year: '2-digit', hour: "2-digit", minute: "2-digit"
            };
            var date = new Date(timestring);
            return date.toLocaleTimeString('en-us',options);
        },
        remove: function(note_id){
            var app = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: window.location.href + '/' + note_id + "/delete",
                dataType: 'json',
                data: {},
                success: function(data){
                    app.get_data();
                },
                error: function(data){
                    console.log(data);
                }
            });
        },
        add_note: function(){
            var validated = true;
            if (!this.note.text){
                this.note.error = "Note is required";
                validated = false;
            }
            if (this.note.text.length > 255){
                this.note.error = "Note must be less than 255 characters";
                validated = false;
            }
            if (!validated){
                return
            }
            this.note.error = "";

            var app = this;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: window.location.href,
                dataType: 'json',
                data: {note: app.note.text},
                success: function(data){
                    app.get_data();
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
        get_data: function(){
            this.rows = [
                {
                    'name' : 'Loading...',
                    'note' : 'Loading...',
                    'payment' : 'Loading...',
                }
            ]
            $.get({
                url: window.location.href + "/json",
                data: {
                    page: this.page,
                    sort_col: this.sort_col,
                    sort_dir: this.sort_dir,
                },
                success: function(data){
                    app.maxPages = data.last_page;
                    app.rows = data.data;
                }
            });
        }
    },
    watch:{
        page: function (val) {
            this.get_data();
        }
    },
    created: function () {
        this.get_data();
    }

});

