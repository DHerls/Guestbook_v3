
$(document).ready(function() {
    var editing = false;
    var canceling = false;

    function addTextbox() {
        if (!editing) {
            var html = "<input type='text'"
            if ($(this).html() != 0) {
                html += " value='" + $(this).html() + "'";
            }
            html += "class='text-center form-control'>";
            $(this).html(html);
            $(this).removeClass('memberCountDisplay');
            $(this).addClass('memberCountEdit');
            $(this).find('input').select();
            editing = true;
        }

    }

    function removeTextbox(){
        var td = $('td.memberCountEdit')
        var val = td.find('input').val().trim();
        if (val==""){
            td.html(0);
        } else {
            td.html(val);
        }

        td.addClass('memberCountDisplay');
        td.removeClass('memberCountEdit');
        editing = false;
    }

    function cancelEdit(){
        canceling = true;
        var input = $(this).find('input');
        input.val(input[0].defaultValue);
        removeTextbox.call(this);
        canceling = false;
    }

    function keyHandler() {
        switch (event.which){
            //Enter button pressed
            case 13:
                submitRecord.call(this);
                break;
            //Escape Button Pressed
            case 27:
                cancelEdit.call(this)
                break;
        }
    }

    function submitRecord() {
        if (canceling){
            return;
        }
        var input = $(this).find('input');
        var val = input.val().replace(' ','');
        //Input isn't a number
        if (/[^0-9]+/.test(val)){
            event.preventDefault();
            $.notifyBar({
                cssClass: "warning",
                html: "Must be a number!"
            });
            $(this).addClass('has-error');
            input.select();
        } else {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "/members/" + $(this).parent().attr('id') + '/records',
                dataType: 'json',
                data: {num_members: val},
                success: function(data){
                    removeTextbox.call(this);
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
                    $(this).addClass('has-error');
                    input.select();
                }
            });

        }
    }

    $("#memberTable").tablesorter( {sortList: [[0,0]]} );

    $(document).on('dblclick','td.memberCountDisplay',addTextbox);
    $(document).on('focusout','td.memberCountEdit',submitRecord);
    $(document).on('keyup','td.memberCountEdit',keyHandler);

    $(document).on('paste','td.memberCountEdit',function() {
        event.preventDefault();
    });
});