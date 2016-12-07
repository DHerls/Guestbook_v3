<template>
    <div class="panel panel-info">
        <div v-once class="panel-heading">
            {{title}}
            <button class="btn btn-default pull-right glyphicon glyphicon-plus" v-on:click="addRow"></button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <table class="table table-responsive form-group">
                <thead>
                    <th v-for="title in columns.titles">{{title}}</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr v-for="object, index in rows">
                        <td v-for="key in columns.keys" v-bind:class="{ 'has-error': object.errors[key] }">
                            <p v-if="object.errors[key]">{{object.errors[key]}}</p>
                            <input type="text" v-model="object[key]" class="form-control">
                        </td>
                        <td v-if="index != 0" class="fit"><button v-on:click="remove(index)" class="btn btn-danger text-center glyphicon glyphicon-remove"></button></td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
</template>
<style>
    .table td.fit,
    .table th.fit {
        white-space: nowrap;
        width: 1%;
    }
</style>
<script>
    export default{
        data(){
            return{
                msg:'hello vue',
            }
        },
        props: [
            'title',
            'columns',
            'rows'
        ],
        methods: {
            addRow: function () {
                var obj = {};
                for (var i = 0; i< this.columns.keys.length; i++){
                    obj[this.columns.keys[i]] = '';
                }
                obj.errors = {};
                this.rows.push(obj);
            },
            remove: function(rowNum){
                this.rows.splice(rowNum,1);
            }
        },
        mounted: function(){
            this.addRow();
        }
    }
</script>
