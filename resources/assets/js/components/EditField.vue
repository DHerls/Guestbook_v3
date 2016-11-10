<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <td v-bind:class="{ 'has-error': has_error }">
        <input v-if="editing" type="text" v-model="dataobj[key_col]"  class="text-center form-control" @blur="select()" @keyup.enter="submit()" @keyup.esc="cancel()">
        <p v-else class="text-center" @dblclick="edit()">{{dataobj[key_col]}}</p>
    </td>
</template>
<style>
</style>
<script>
    export default{
        data(){
            return{
                old_value: "",
                editing: false,
                has_error: false,
            }
        },
        props: ['dataobj','key_col','submit_url','submit_func'],
        methods: {
            edit: function(){
                this.old_value = this.dataobj[this.key_col];
                this.editing = true;
                Vue.nextTick(this.select.bind(this));

            },
            select: function(){
                this.$el.getElementsByTagName('input')[0].select();
            },
            submit: function() {
                if (this.dataobj[this.key_col] == this.old_value){
                    return;
                }
                var toSend = {};
                toSend[this.key_col] = this.dataobj[this.key_col];
                this.submit_func(toSend,this.submit_url, this.callback);
            },
            callback: function(result){
                if (result){
                    this.has_error = false;
                    this.editing = false;
                } else {
                    this.has_error = true;
                }
            },
            cancel: function() {
                this.dataobj[this.key_col] = this.old_value;
                this.editing = false;
            }
        }
    }
</script>
