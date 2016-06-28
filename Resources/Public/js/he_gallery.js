$(document).ready(function(){

    /* default settings */
    $('.venobox').venobox();

    /* auto-open #firstlink on page load */
    $("#firstlink").venobox().trigger('click');
});
