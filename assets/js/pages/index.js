
$(document).ready(function () {
    $('.adm_close').click(function () {
        $('.adm_div').fadeOut();
        $('.db_response').html("");
        $('.db_response_div').hide();
        $('.uname').val("");
        $('.pwd').val("");
    });

});