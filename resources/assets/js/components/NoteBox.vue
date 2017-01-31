<template>
    <div>
    <div class="panel panel-info">
        <div class="panel-heading">
            <a :href="here() + '/notes'" class="link-muted">Notes</a>
            <button type="button" class="btn btn-default pull-right" data-toggle="modal"
                    data-target="#noteModal" title="Add Note">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
            <div class="clearfix"></div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>User</th>
                <th>Note</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="note, index in notes">
                <td>{{note.date}}</td>
                <td>{{note.time}}</td>
                <td>{{note.name}}</td>
                <td>{{note.note}}</td>
                <td class="fit"><button v-on:click="remove(index,note.id)" class="btn btn-default btn-sm text-center" title="Remove Note">
                    <i class="glyphicon glyphicon-remove"></i>
                </button></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal fade" role="dialog" id="noteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Add Note</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="noteText">Note:</label>
                            <p class="error-msg" v-if="note.error">{{ note.error }}</p>
                            <input type="text" id="noteText" class="form-control" v-model="note.text">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <button class="btn btn-primary pull-right" v-on:click="addNote()">Add Note</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                notes: [],
                note: {
                    text: "",
                    error: ""
                }
            }
        },
        methods: {
            here: function () {
                return window.location.href;
            },
            addNote: function(){
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
                    url: window.location.href + "/notes",
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
            remove: function(index, id){
                var app = this;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: window.location.href + "/notes/" + id + "/delete",
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
            get_data(){
                var app = this;
                $.get({
                    url: window.location.href + "/notes/quick",
                    success: function(data){
                        app.notes = data;
                    }
                });
            }
        },
        created: function(){
            this.get_data();
        }
    }
</script>