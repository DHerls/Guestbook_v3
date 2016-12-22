<template>
    <div class="btn-group">
        <button v-for="(value,key) in values" v-on:click="setSelected(key)" class="btn" v-bind:class="{ 'btn-primary': selected === key, 'btn-default': selected !== key }">{{value}}</button>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                selected: ""
            }
        },
        methods: {
            setSelected: function (selection) {
                this.selected = selection;
                this.$emit('select',this.selected);
            }
        },
        created: function(){
            this.selected = this.default;
        },
        props: ['values','default']
    }
</script>