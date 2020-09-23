// sets the hash of the fancybox on false
$.fancybox.defaults.hash = false;

let $btns = $('.my-btn').click(function() {
    if (this.id == 'all') {
        $('.masonry > article').show();
    } else {
        let $el = $('.' + this.id).show();
        $('.masonry > article').not($el).hide();
    }
    $btns.removeClass('active');
    $(this).addClass('active');
});