$(document).ready(function(){

    /* default settings */
    $('.venobox').venobox();

    /* auto-open #firstlink on page load */
    $("#firstlink").venobox().trigger('click');


    $('#grid-gallery').masonry({
        itemSelector: '.grid-item',
        columnWidth: 300
    });
});
