const app = new Vue({
    el: "#app",
    data: {

    },
    methods: {
        show_confirm: function(){
            $.confirm({
                body: "Are you sure you want to delete this user?  This cannot be undone.",
                class: "danger",
                button: "Delete",
                confirm: function(){
                    console.log("Action Confirmed");
                }
            });
        }
    }
});