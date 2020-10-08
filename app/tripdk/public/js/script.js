// sets the hash of the fancybox on false
$.fancybox.defaults.hash = false;

// sets the filter button op active and hides all where the id is not equal to a class in the .masonry > article
let $btns = $('.my-btn').click(function() {
    if (this.id == 'all') {
        $('.masonry > article').show();
    } else {
        let $el = $('.' + this.id).show();
        $('.masonry > article').not($el).hide();
        $('.masonry > article').removeClass('show');
        $('.masonry > article.'+this.id).addClass('show');
    }
    $btns.removeClass('active');
    $(this).addClass('active');
});

//variable
let x = document.getElementById("inputPassword");
let y = document.getElementById("icon");

// function for showing the password as text
document.getElementById("labelIcon").addEventListener('click', function() {
    if (x.type === "password") {
        x.type = "text";
        y.className = "far fa-eye";

    } else {
        x.type = "password";
        y.className = "far fa-eye-slash";
    }
});


