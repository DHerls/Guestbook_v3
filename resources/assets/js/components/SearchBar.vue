<template xmlns:v-on="http://www.w3.org/1999/xhtml">
    <div class="input-group">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-search"></i>
            </span>
        <input id="member-search" role="search" type="search" class="form-control" v-bind:value="value" v-on:input="update_value($event.target.value)" @keyup.esc="update_value('')"/>
        <div class="input-group-btn">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Search By<span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right">
                <template v-for="col, key, index in search_columns">
                <li  v-on:click="set_search_col(key)"><a href="#">{{col}}
                    <span><i class="glyphicon glyphicon-ok" v-if="search_column == key || (index == 0 && search_column=='')"></i></span>
                    </a>
                </li>
                </template>
            </ul>
        </div>
    </div>
</template>
<style>
</style>
<script>
    export default{
        data(){
            return{
                search_column: '',
                old_search: ''
            }
        },
        props: ['search_columns','value'],
        methods: {
            update_value: function(new_value) {
                if (new_value == this.old_search){
                    return;
                }
                this.old_search = new_value
                if (this.search_column== 0){
                    this.search_column = this.search_columns[0];
                }
                this.$emit('input', new_value);
            },
            set_search_col(key){
                this.search_column = key;

                //Invalidate old search so search function fires
                this.old_search = "";
                this.search();
            }
        }
    }
</script>
