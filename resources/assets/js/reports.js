Vue.component('datepicker',require('./components/Datepicker.vue'));

function today(){
    var tempDate = new Date();
    return new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate());
}

const app = new Vue({
    el: '#app',
    data: {
        guest_start: today(),
        guest_end: today(),
        member_start: today(),
        member_end: today()
    },
    methods: {
        get_guest: function(){
            var data = {
                start_year: this.guest_start.getFullYear(),
                start_month: this.guest_start.getMonth() + 1,
                start_date: this.guest_start.getDate(),
                end_year: this.guest_end.getFullYear(),
                end_month: this.guest_end.getMonth() + 1,
                end_date: this.guest_end.getDate()
            }
            var params = Object.keys(data).map(function(k) {
                return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
            }).join('&')
            window.location.href = window.location.href + "/guests?" + params;
        },
        get_member: function(){
            var data = {
                start_year: this.member_start.getFullYear(),
                start_month: this.member_start.getMonth() + 1,
                start_date: this.member_start.getDate(),
                end_year: this.member_end.getFullYear(),
                end_month: this.member_end.getMonth() + 1,
                end_date: this.member_end.getDate()
            }
            var params = Object.keys(data).map(function(k) {
                return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
            }).join('&')
            window.location.href = window.location.href + "/members?" + params;
        }
    }
});
