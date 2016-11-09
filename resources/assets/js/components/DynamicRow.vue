<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <tr>
        <td v-for="column in columns">
            <p v-if="column.type == 'text'">{{info[column.key]}}</p>
            <edit-field v-if="column.type == 'efield'" v-bind:data_obj="get_data_obj(column.key)" v-on:submit="submit()"/>
            <a v-if="column.type == 'btn'" v-bind:href="get_info_data(column.link)" class="btn btn-info" role="button">
                <span v-if="column.icon != 0" v-bind:class="'glyphicon glyphicon-' + column.icon"></span>
                <span v-if="column.key != 0">{{get_info_data(column.key)}}</span>
            </a>
        </td>
    </tr>
</template>
<style>

</style>
<script>
    import EditField from './EditField.vue'
    export default{
        data(){
            return{
                msg:'hello vue',
                data_obj: {
                        has_error: false,
                        editing: false,
                        data: 0
                }
            }
        },
        props: ['columns','info'],
        components: {
            EditField
        },
        methods: {
            get_data_obj: function(key){
                this.data_obj.data = this.info[key];
                return this.data_obj;
            },
            get_info_data: function(key){
                //If text is from method
                if (key.endsWith('()')){
                    var method = key.substring(0,key.length-2);
                    return this.info[method]();
                } else {
                    return this.info[key];
                }
            },
            submit: function(){
                this.data_obj.editing = false;
            }
        }
    }
</script>
