<template>
    <div class="panel panel-info">
        <div v-once class="panel-heading">{{title}}<button class="pull-right">+</button></div>
        <div class="panel-body">
            <table>
                <tr v-for="object in data">
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
                msg:'hello vue'
            }
        },
        props: [
            'title',
            'data',
            'columns'
        ]
    }
</script>
