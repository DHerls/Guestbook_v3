<template xmlns:v-bind="http://www.w3.org/1999/xhtml">
    <td v-bind:class="{ 'has-error': has_error }">
        <input v-if="editing" type="text" v-model="text"  class="text-center form-control" @blur="submit()" @keyup.enter="submit()" @keyup.esc="cancel()">
        <p v-else class="text-center" @dblclick="edit()">{{data_obj.data}}</p>
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
                has_error: false
            }
        },
        props: ['text'],
        methods: {
            edit: function(){
                this.old_value = this.data;
                this.editing = true;
                Vue.nextTick((function() {
                    this.$el.getElementsByTagName('input')[0].select();
                }).bind(this));

            },
            submit: function() {
                this.$emit('submit')
            },
            cancel: function() {
                this.data = this.old_value;
                this.editing = false;
            }
        }
    }
</script>
