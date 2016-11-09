<template>
    <tbody>
        <dynamic-row v-for="member in sorted_members" v-bind:columns="columns" v-bind:info="member"></dynamic-row>
    </tbody>
</template>

<style>
</style>
<script>
    import DynamicRow from './DynamicRow.vue'
    export default{
        data()  {
            return {

            }
        },
        components: {
            DynamicRow
        },
        props: ['members', 'columns'],
        computed: {
            sorted_members: function(){
                return this.members.sort(function(a,b){
                    if (typeof(a[app.sort_col]) == 'string'){
                        return (app.sort_dir=='down' ? 1 : -1) * a[app.sort_col].localeCompare(b[app.sort_col]);
                    } else {
                        return (app.sort_dir=='down' ? 1 : -1) * (a[app.sort_col] - (b[app.sort_col]));
                    }
                });
        }
    }

    }
</script>
