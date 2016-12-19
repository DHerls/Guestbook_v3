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
            <table class="table table-responsive form-group">
                <thead>
                    <th v-for="column in info.columns">{{column.title}}</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="object, index in info.rows">
                        <td  v-for="column in info.columns" v-bind:class="{ 'has-error': object.errors[column.key] }">
                            <input v-if="editing" type="text" v-model="object[column.key]" class="form-control">
                            <p v-else>{{object[column.key]}}</p>
                            <p class="error-msg" v-if="object.errors[column.key]">{{object.errors[column.key]}}</p>
                        </td>
                        <td v-if="index != 0" class="fit"><button v-if="editing" v-on:click="remove(index)" class="btn btn-danger text-center glyphicon glyphicon-remove"></button></td>
                    </tr>
                </tbody>

            </table>
            <button v-if="editing" class="btn btn-default pull-right add glyphicon glyphicon-plus" v-on:click="addRow"></button>
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
<script>
    var jquery = require("jquery");
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
                this.info.rows.splice(rowNum,1);
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
            confirm: function() {
                if (!this.rowsChanged()){
                    this.editing = false;
                    return;
                }

                var validated = true;
                var message;

                //Validate fields
                var row;
                var col;
                
                for(var i_row = 0; i_row < this.info.rows.length; i_row++){
                    row = this.info.rows[i_row];
                    if (this.info.required || !this.rowIsEmpty(row)) {
                        row.errors = {};
                        for (var i_col = 0; i_col < this.info.columns.length; i_col++){
                            col = this.info.columns[i_col];
                            message = validator.validate(row[col.key],
                                    col.validation,
                                    col.title);
                            if (message){
                                row.errors[col.key] = message;
                                validated = false;
                            }
                        }
                    }
                }

                if (validated){
                    var rows = [];
                    var newRow;
                    for (var i_row = 0; i_row < this.info.rows.length; i_row++){
                        row = this.info.rows[i_row];

                        if (i_row != 0 || !this.rowIsEmpty(row)){
                            newRow = {};
                            for (var col = 0; col < this.info.columns.length; col++){
                                if (row[this.info.columns[col].key]) {
                                    newRow[this.info.columns[col].key] = row[this.info.columns[col].key];
                                }
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
    }
</script>
