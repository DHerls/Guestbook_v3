Vue.component('multiple-display',require('./../components/MultipleDisplayEditBox.vue'));
Vue.component('note-box',require('./../components/NoteBox.vue'));
Vue.component('balance-box',require('./../components/BalanceBox.vue'));
var memberFields = require('./../json/memberFields.json');
var addressFields = require('./../json/addressFields.json');

const app = new Vue({
    el: "#app",
    data: {
        info: memberFields,
        address: addressFields
    },
    methods: {

    },
    created: function() {
        $.get({
            url: window.location.href + "/json",
            success: function(data){
                //For each category in memberFields
                for (var title in app.info){
                    //Insert data
                    for (var i = 0; i < data[title].length; i++){
                        var row = {};
                        var errors = {};
                        for (var j = 0; j < app.info[title].columns.length; j++){
                            row[app.info[title].columns[j].key] = data[title][i][app.info[title].columns[j].key];
                            errors[app.info[title].columns[j].key] = "";
                        }
                        row.errors = errors;
                        app.info[title].rows.push(row);
                    }

                }

                row = {};
                for (var i = 0; i < app.address.columns.length; i++){
                    row[app.address.columns[i].key] = data[app.address.columns[i].key];
                }
                row.errors = {};
                app.address.rows.push(row);
            }
        });
    }
});