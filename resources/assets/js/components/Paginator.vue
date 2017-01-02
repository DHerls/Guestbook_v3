<template>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-default btn-arrow" @click="firstPage"><i class="glyphicon glyphicon-step-backward"></i></button>
        <button class="btn btn-default btn-arrow" @click="prevPage"><i class="glyphicon glyphicon-chevron-left"></i></button>
        <button class="btn btn-number" :class="{'btn-primary': n == value, 'btn-default': n!=value}" v-for="n in range" @click="setPage(n)">{{n}}</button>
        <button class="btn btn-default btn-arrow" @click="nextPage"><i class="glyphicon glyphicon-chevron-right"></i></button>
        <button class="btn btn-default btn-arrow" @click="lastPage"><i class="glyphicon glyphicon-step-forward"></i></button>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                selected: ""
            }
        },
        props: ['value', 'max'],
        methods: {
            firstPage: function(){
                this.setPage(1)
            },
            lastPage: function(){
                this.setPage(this.max);
            },
            prevPage: function(){
                if (this.value > 1){
                    this.setPage(this.value -1);
                }
            },
            nextPage: function(){
                if (this.value < this.max){
                    this.setPage(this.value + 1);
                }
            },
            setPage: function(n){
                this.$emit('input', n);
            }
        },
        computed: {
            range: function(){
                var range = [];
                if (this.max <= 5){
                    for (var i = 1; i <= this.max; i++){
                        range.push(i);
                    }
                } else {
                    if (Math.abs(1-this.value) >= 2 && Math.abs(this.max-this.value) >= 2){
                        for (var i = -2; i <= 2; i++){
                            range.push(i+ this.value);
                        }
                    } else if (Math.abs(1-this.value) >= 2){
                        for (var i = this.max-4; i <= this.max; i++){
                            range.push(i);
                        }
                    } else {
                        for (var i = 1; i <= 5; i++){
                            range.push(i);
                        }
                    }
                }
                return range;
            }
        }
    }
</script>
<style>
    button.btn.btn-default.btn-arrow {
        padding-left: 2px;
        padding-right: 2px;
    }
    button.btn.btn-default.btn-number {
        padding-left: 5px;
        padding-right: 5px;
        width: 30px;
        height: 30px;
    }
</style>