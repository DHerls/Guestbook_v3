const app = new Vue({
   e1: "#app",
    data: {
        sort_col: "",
        data: [],
        data_url: ""
    },
    methods: {
        set_sort_col: function(column){
            this.sort_col = column;
        },

        search: function(text, column){

        }
    },
    computed: {
        sorted_data: function() {

        }
    }

});