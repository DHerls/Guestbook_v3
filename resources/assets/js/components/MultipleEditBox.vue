<template>
    <div class="panel panel-info">
        <div v-once class="panel-heading">
            {{info.title}}
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
                        <td v-for="column in info.columns" v-bind:class="{ 'has-error': object.errors[column.key] }">
                            <input v-if="column.type != 'checkbox'" type='text' v-model="object[column.key]" class="form-control">
                            <input v-else type='checkbox' v-model="object[column.key]" class="form-control">
                            <p class="error-msg" v-if="object.errors[column.key]">{{object.errors[column.key]}}</p>
                        </td>
                        <td v-if="index != 0" class="fit"><button v-on:click="remove(index)" class="btn btn-danger text-center glyphicon glyphicon-remove"></button></td>
                    </tr>
                </tbody>

            </table>
            <button v-if="!info.limit || info.rows.length < info.limit" class="btn btn-default pull-right add glyphicon glyphicon-plus" v-on:click="addRow"></button>

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
    export default{
        data(){
            return{
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
                    if (this.info.columns[i].type != 'checkbox'){
                        obj[this.info.columns[i].key] = '';
                    } else {
                        obj[this.info.columns[i].key] = false;
                    }
                    obj.errors[this.info.columns[i].key] = '';
                }
                this.info.rows.push(obj);
            },
            remove: function(rowNum){
                this.info.rows.splice(rowNum,1);
            }
        },
        mounted: function(){
            this.addRow();
        }
    }
</script>
