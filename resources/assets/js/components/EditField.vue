<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <td v-bind:class="{ 'has-error': has_error }">
        <input v-if="editing" type="text" v-model="current_value"  class="text-center form-control" @blur="select()" @keyup.enter="submit()" @keyup.esc="cancel()">
        <p v-else class="text-center" @dblclick="edit()">{{current_value}}</p>
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
                current_value: "",
            }
        },
        props: ['text','key_col','submit_url','submit_func'],
        methods: {
            edit: function(){
                this.old_value = this.text;
                this.editing = true;
                Vue.nextTick(this.select.bind(this));

            },
            select: function(){
                this.$el.getElementsByTagName('input')[0].select();
            },
            submit: function() {
                if (this.current_value == this.old_value){
                    return;
                }
                var obj = {};
                obj[this.key_col] = this.current_value;
                var result = this.submit_func(obj,this.submit_url);
                console.log(result);
                if (result){
                    this.editing = false;
                }

            },
            cancel: function() {
                this.text = this.old_value;
                this.editing = false;
            }
        },
        created: function() {
            this.current_value = this.text;
        }
    }
</script>
