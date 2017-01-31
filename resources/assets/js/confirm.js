(function ( $ ) {
    $.confirm = function( options ) {
        $("#confirmModal").remove();

        var modal = "<div id='confirmModal' class='modal' role='dialog'> " +
            "<div class='modal-dialog'> " +
            "<div class='modal-content'> " +
            "<div class='modal-header'> " +
            "<button type='button' class='close' data-dismiss='modal'>&times;</button> " +
            "<h4 class='modal-title'>Confirm Action</h4> " +
            "</div> " +
            "<div class='modal-body'> " +
            "<p>" + options.body +"</p> " +
            "</div> " +
            "<div class='modal-footer'> " +
            "<button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button> " +
            "<button id='confirmModalConfirm' type='button' class='btn btn-" + (options.class ? options.class : "default") + "'>" +
            (options.button ? options.button : "Confirm") +
            "</button> " +
            "</div> " +
            "</div> " +
            "</div>"
        $("body").append(modal);

        $("#confirmModalConfirm").click(function() {
            options.confirm();
            $("#confirmModal").modal("hide");
        });

        $("#confirmModal").modal("show");

    };
}(jQuery));