<template>
    <tr v-for="member in members" v-bind:id="'member-'+member.id">
        <td><a v-bind:href="'/members/' + member.id" class="btn btn-info" role="button">
            <span class="glyphicon glyphicon-list-alt"></span>
        </a>
        </td>
        <td>@{{member.first_name}}</td>
        <td>@{{member.last_name}}</td>
        <td v-if="member.editing" v-bind:class="{ 'has-error': member.has_error }">
            <input type="text" v-model="member.num_members"  class="text-center form-control" @blur="member.submit()" @keyup.enter="member.submit()" @keyup.esc="member.cancel()">
        </td>
        <td v-else class="text-center memberCountDisplay" @dblclick="member.edit()">@{{member.num_members}}</td>

        <td class="text-center">@{{member.num_guests}}</td>
    </tr>
</template>

<style>
</style>
<script>

function member(ids, first, last, mem, guests){
    this.id = ids;
    this.first_name = first;
    this.last_name = last;
    this.num_members = mem;
    //Necessary for "cancel"
    this.old_members = mem;
    this.num_guests = guests;

    //DOM Control booleans
    this.editing = false;
    this.has_error = false;

    this.edit = function() {
        this.editing = true;
        this.old_members = this.num_members;
        Vue.nextTick((function() {
            document.querySelector("tr#member-"+this.id + " td input").select();
        }).bind(this));
    }

    this.cancel = function() {
        this.canceling = true;
        this.num_members = this.old_members;
        this.editing = false;
        this.has_error = false;
    }

    this.submit = function() {
        var self = this;
        //Prevents submissions when pressing escape
        if (this.canceling){
            this.canceling = false;
            return;
        }
        var input = document.querySelector("tr#member-"+this.id + " td input")
        //Input isn't a number
        if (/[^0-9]+/.test(this.num_members)) {
            $.notifyBar({
                cssClass: "warning",
                html: "Must be a number!"
            });
            this.has_error = true;
            input.select();
            return;
        }

        if (this.num_members === ""){
            $.notifyBar({
                cssClass: "warning",
                html: "Members must not be blank"
            });
            this.has_error = true;
            input.select();
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "/members/" + self.id + '/records',
            dataType: 'json',
            data: {num_members: self.num_members},
            success: function(data){
                self.has_error = false;
                self.editing = false;
                self.old_members = self.num_members;
                return;
            },
            error: function(data){
                var message = "Generic Error";
                var cl = "error";
                switch (data.status){
                    case 404:
                        message = "Cannot connect to server";
                        cl = "error";
                        break;
                    case 422:
                        message = data.responseJSON['num_members'][0];
                        cl = "warning";
                        break;
                    case 500:
                        message = "Internal Server Error";
                        cl = "error";
                        console.log(data);
                    default:

                }
                $.notifyBar({
                    cssClass: cl,
                    html: message
                });
                self.has_error = true;
                input.select();
            }
        });

    }
}
    export default{
        data()  {
            return {members: [new member(5,"Dan","Herlihy",5,2)]}
        }
    }
    //this.members.push(new member(5,"Dan","Herlihy",5,2));
</script>
