<template>
    <ul>
        <li v-for="n in 10">{{n}}</li>
    </ul>
</template>
<style>
</style>
<script>
    //import HeaderComponent from './components/header.vue'
    //import OtherComponent from './components/other.vue'
    export default{
        data(){
            return{
                msg:'hello vue'
            }
        }
    }
</script>
