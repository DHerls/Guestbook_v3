<template>
    <div class="panel panel-info">
        <div v-once class="panel-heading">
            {{title}}
            <button class="btn btn-default pull-right glyphicon glyphicon-plus" v-on:click="addRow"></button>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <table>
                <tr v-for="object in rows">
                    <td><input type="text" v-model="object[columns[0]]"></td>
                    <td><input type="text" v-model="object[columns[1]]"></td>
                </tr>
            </table>
        </div>
    </div>
</template>
<style>
</style>
<script>
    export default{
        data(){
            return{
                msg:'hello vue',
                rows: []
            }
        },
        props: [
            'title',
            'columns'
        ],
        methods: {
            addRow: function(){
                this.rows.add()
            }
        }
    }
</script>
