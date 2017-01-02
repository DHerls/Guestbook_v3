<template>
    <div class="datepicker">
        <input type="text" class="form-control datepicker-input" v-on:click="inputClick" :value="dateString" readonly>
        <div class="popup" v-if="show">
            <div class="text-center">
                <a class="pull-left" @click="prevMonth"><i class="glyphicon glyphicon-menu-left"></i></a>
                <span class="text-center" @click="nextMonth">{{months[curMonth]}} {{curYear}}</span>
                <a class="pull-right" @click="nextMonth"><i class="glyphicon glyphicon-menu-right"></i></a>
            </div>
            <div class="divider"></div>
            <table class="text-center">
                <thead>
                <tr>
                    <th class="text-center" v-for="day in daysOfWeek">{{day}}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="week in month">
                    <td v-for="date in week" class="datepicker-item" :class="{'non-month': isNonMonth(date), 'selected': date.getTime() === value.getTime(), 'disabled': isDisabled(date)}" @click="select(date)">
                        {{date.getDate()}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                curMonth: 0,
                curYear: 0,
                fom: {},
                lom: {},
                show: false,
                daysOfWeek: ['Su','Mo','Tu','We','Th','Fr','Sa'],
                months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            }
        },
        props: ['value','start','end'],
        beforeDestroy () {
            $(window).off('click', this._blur)
        },
        methods: {
            close: function(){ this.show = false},
            inputClick: function(){
                this.setView(this.value.getMonth(),this.value.getFullYear());
                this.show = !this.show;
            },
            weeksInMonth: function(month, year){
                if (new Date(year,month,1).getDay() - new Date(year,month,this.daysInMonth(month,year)).getDay() >= 5){
                    return 6;
                }  else {
                    return 5;
                }
            },
            daysInMonth: function(month, year){
                return 32 - new Date(year, month, 32).getDate();
            },
            numericDate(dow,week,month,year){
                return new Date(year,month, (week-1)*7 + dow - new Date(year,month,1).getDay()).getDate()
            },
            isNonMonth: function(date){
                return date.getMonth() != this.curMonth
            },
            isDisabled: function(date){
                if (this.start){
                    if (date.getTime() < this.start.getTime()){
                        return true;
                    }
                }
                if (this.end){
                    if (date.getTime() > this.end.getTime()){
                        return true;
                    }
                }
                return false;
            },
            select: function(date){
                if (this.isDisabled(date)){
                    return;
                }
                if (this.curMonth != date.getMonth()){
                    this.setView(date.getMonth(),date.getFullYear());
                }
                this.$emit('input', date);
                this.close();
            },
            setView: function(month, year){
                this.curMonth = month;
                this.curYear = year;
                this.lom = new Date(this.curYear, this.curMonth, this.daysInMonth(this.curMonth,this.curYear));
                this.fom = new Date(this.curYear, this.curMonth, 1);
            },
            prevMonth: function(){
                if (this.curMonth == 0){
                    this.setView(11,this.curYear-1);
                } else {
                    this.setView(this.curMonth-1, this.curYear);
                }
            },
            nextMonth: function(){
                if (this.curMonth == 11){
                    this.setView(0,this.curYear+1);
                } else {
                    this.setView(this.curMonth+1, this.curYear);
                }
            }
        },
        computed: {
            dateString: function(){
                return this.value.toLocaleDateString("en-US")
            },
            month: function(){
                var weeks = [];
                for (var iWeek = 0; iWeek < this.weeksInMonth(this.curMonth,this.curYear); iWeek++){
                    var week = [];
                    for (var iDay = 1; iDay <= 7; iDay++){
                        week.push(new Date(this.curYear,this.curMonth, iWeek * 7 + iDay - this.fom.getDay()))
                    }
                    weeks.push(week);
                }
                return weeks;
            },
        },
        created(){
            this.setView(this.value.getMonth(),this.value.getFullYear());
            this._blur = (e) => {
                if (!this.$el.contains(e.target)) this.close()
            }
            $(window).on('click', this._blur)
        }
    }
</script>
<style>
    div.popup {
        width: 200px;
        background: white;
        padding: 10px;
        position: absolute;
        z-index: 1000;
        border: 1px solid grey;
        border-radius: 5px;
    }
    div.datepicker {
        display: inline-block;
    }
    input.form-control.datepicker-input[readonly] {
        background: white;
        width: 200px;
    }
    table {
        width: 100%;
    }
    td.datepicker-item:hover {
        background: rgba(30, 144, 255, 0.22);
    }
    td.datepicker-item {
        width: 25px;
        height: 25px;
        cursor: default;
        border-radius: 4px;
    }
    td.non-month {
        color: lightgrey;
    }
    td.selected {
        background: dodgerblue;
        color: white;
    }
    td.selected.non-month {
        color: lightgrey;
        border: solid 1px dodgerblue;
        background: white;
    }
    td.selected.non-month:hover {
        background: dodgerblue;
        color: white;
    }
    td.datepicker-item.disabled {
        background: #c5c5c5;
        color: #8a8282;
        border-radius: 0;
    }
    div.divider {
        width: 100%;
        border-top: 1px solid black;
        margin-top: 5px;
        margin-bottom: 10px;
    }
</style>