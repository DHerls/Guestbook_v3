<!--suppress XmlUnboundNsPrefix -->
<template>
    <div class="panel panel-info">
        <div class="panel-heading">
            {{info.title}}
            <span  v-if="editing" class="pull-right">
                    <button class="btn btn-default glyphicon glyphicon-remove" v-on:click="cancel"></button>
                    <button class="btn btn-success glyphicon glyphicon-ok" v-on:click="confirm"></button>
            </span>
            <button v-else class="btn btn-default pull-right glyphicon glyphicon-pencil" v-on:click="edit"></button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <table v-if="editing" class="table table-responsive form-group">
                <thead>
                    <th v-for="column in info.columns">{{column.title}}</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="object, index in info.rows">
                        <td  v-for="column in info.columns" v-bind:class="{ 'has-error': object.errors[column.key] }">
                            <input type="text" v-model="object[column.key]" class="form-control">
                            <p class="error-msg" v-if="object.errors[column.key]">{{object.errors[column.key]}}</p>
                        </td>
                        <td v-if="index != 0 || !info.required" class="fit">
                            <button v-if="editing" v-on:click="remove(index)" class="btn btn-danger text-center glyphicon glyphicon-remove"></button>
                        </td>
                    </tr>
                </tbody>

            </table>
            <ul v-else>
                <li v-for="object in info.rows">{{display(object)}}</li>
            </ul>
            <button v-if="editing && (!info.limit || info.rows.length < info.limit)" class="btn btn-default pull-right add glyphicon glyphicon-plus" v-on:click="addRow"></button>
        </div>
    </div>
</template>
<style>
    .table td.fit,
    .table th.fit {
        white-space: nowrap;
        width: 1%;
    }

    .table{
        margin-bottom: 8px;
    }

    button.add {
        margin-right: 7px;
    }
</style>
<!--suppress JSUnresolvedVariable -->
<script>
    import {validator} from "../validator"
    export default{
        data(){
            return{
                editing: false,
                oldRows: {}
            }
        },
        props: [
            'info'
        ],
        methods: {
            addRow: function () {
                var obj = {};
                obj.errors = {};
                for (var i = 0; i< this.info.columns.length; i++){
                    obj[this.info.columns[i].key] = '';
                    obj.errors[this.info.columns[i].key] = '';
                }
                this.info.rows.push(obj);
            },
            remove: function(rowNum){
                if (rowNum == 0 && this.info.rows.length == 1){
                    for (var i = 0; i < this.info.columns.length; i++){
                        this.info.rows[0][this.info.columns[i].key] = "";
                        this.info.rows[0].errors[this.info.columns[i].key] = "";
                    }
                } else {
                    this.info.rows.splice(rowNum, 1);
                }
            },
            edit: function(){
                this.editing = true;
                this.oldRows = this.cloneRows(this.info.rows);
                if (this.info.rows.length == 0){
                    this.addRow();
                }
            },
            cancel: function(){
                this.editing = false;
                this.info.rows = this.cloneRows(this.oldRows);
            },
            cloneRows: function(origin){
                var newRows = [];
                var newRow;
                var errors;
                for (var row = 0; row < origin.length; row++){
                    newRow = {};
                    errors = {};
                    for (var col = 0; col < this.info.columns.length; col++){
                        newRow[this.info.columns[col].key] = origin[row][this.info.columns[col].key];
                        errors[this.info.columns[col].key] = "";
                    }
                    newRow.errors = errors;
                    newRows.push(newRow);
                }
                return newRows;
            },
            rowsChanged: function(){
                if (this.info.rows.length != this.oldRows.length){
                    return true;
                }
                for (var row = 0; row < this.info.rows.length; row++){
                    for (var col = 0; col < this.info.columns.length; col++){
                        if (this.info.rows[row][this.info.columns[col].key] != this.oldRows[row][this.info.columns[col].key]){
                            return true;
                        }
                    }
                }
                return false;
            },
            rowIsEmpty: function(row){
                for (var column in row){
                    if (column != 'errors' && row[column]){
                        return false;
                    }
                }
                return true;

            },

            display: function(row){
                var string = "";
                var col;
                for (var i_col = 0; i_col < this.info.columns.length; i_col++){
                    col = this.info.columns[i_col];
                    if (!col.display){
                        if (row[col.key]) {
                            string += row[col.key] + " ";
                        }
                    } else if (!col.regex){
                        if (row[col.key]){
                            string += col.display.replace("%%",row[col.key]) + " ";
                        }
                    } else {
                        var reg = new RegExp(col.regex);
                        var groups = reg.exec(row[col.key]);

                        var disp = col.display;
                        //Matches a display sections formatted {#: replace}
                        var disp_reg = /{([0-9]+):(.*?(?=%%)%%.*?)}/g;

                        var match;
                        //While there are display sections
                        while (match = disp_reg.exec(col.display)){
                            if (groups[match[1]]){
                                //Replace match with the replacement part of the section, with %% substituted
                                disp = disp.replace(match[0],match[2].replace("%%",groups[match[1]]))
                            } else {
                                disp = disp.replace(match[0],"");
                            }
                        }
                        string += disp + " ";
                    }
                }
                return string;
            },
            removeBlanks: function(){
                var to_remove;
                to_remove = [];
                for (var i_row = 0; i_row < this.info.rows.length; i_row++){
                    if (i_row != 0 && this.rowIsEmpty(this.info.rows[i_row])){
                        to_remove.push(i_row);
                    }
                }
                for (var i_row = 0; i_row < to_remove.length; i_row++){
                    this.info.rows.splice(to_remove[i_row]-i_row,1);
                }
            },
            validate: function(){
                var is_validated = true;
                var row;
                var column;
                for (var i_row = 0; i_row < this.info.rows.length; i_row++) {
                    row = this.info.rows[i_row];
                    row.errors = {};

                    //Make sure the form is required or there is data to validate
                    if (this.info.required || !this.rowIsEmpty(row)) {
                        for (var i_col = 0; i_col < this.info.columns.length; i_col++) {
                            column = this.info.columns[i_col];
                            var error = validator.validate(row[column.key],column.validation,column.title);
                            if (error){
                                is_validated = false;
                                row.errors[column.key] = error;
                            }
                        }
                    }
                }

                return is_validated;
            },
            confirm: function() {
                if (!this.validate()){
                    return;
                }

                var rows = [];
                var row;
                var newRow;
                for (var i_row = 0; i_row < this.info.rows.length; i_row++){
                    row = this.info.rows[i_row];

                    if (i_row != 0 || !this.rowIsEmpty(row)){
                        newRow = {};
                        for (var col = 0; col < this.info.columns.length; col++){
                            newRow[this.info.columns[col].key] = row[this.info.columns[col].key];
                        }
                        rows.push(newRow);
                    }
                }

                var dataObj = {};
                dataObj[this.info.url] = rows;

                var app = this;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: window.location.href + "/"  + this.info.url,
                    dataType: 'json',
                    data: dataObj,
                    success: function(data){
                        app.editing = false;
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            }


        }
    }
</script>
