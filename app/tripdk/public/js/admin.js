// variables
const hamburgers = document.querySelectorAll(".hamburger");
let w;

// Popover onclick
$("body").on("click", ".popover-a", function(e) { e.preventDefault() });
$(function () {
    $('[data-toggle="popover"]').popover({
        html:true,
        sanitize: false,
    });
});

// if window resize call responsive function
$(window).resize(function(e) {
    screen_resize();
});

// on document ready
$(document).ready(function(){
    screen_resize();
    $("#menu-toggle").click(function(e){
        e.preventDefault();
        $("#wrapper").toggleClass("menu-closed");
    });
});

// Screen Size, adds and removes classes based on the screen width
function screen_resize() {
    let w = parseInt(window.innerWidth);
    if(w <= 576) {
        forEach(hamburgers, function(hamburger) {
            if (hamburger.classList.contains('is-active')){
                hamburger.classList.toggle("is-active");
            }
        });
        document.getElementById("wrapper").classList.remove("menu-closed");
    }
    else{
        forEach(hamburgers, function(hamburger) {
            if (!hamburger.classList.contains('is-active') && !document.getElementById("wrapper").classList.contains("menu-closed")) {
                hamburger.classList.add('is-active');
            }
            document.getElementById("wrapper").classList.remove("menu-closed");
        });
    }
}

// Name of the file appear on select
$(".custom-file-input").on("change", function() {
    let fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

/**
 * forEach implementation for Objects/NodeLists/Arrays, automatic type loops and context options
 *
 * @private
 * @author Todd Motto
 * @link https://github.com/toddmotto/foreach
 * @param {Array|Object|NodeList} collection - Collection of items to iterate, could be an Array, Object or NodeList
 * @callback requestCallback      callback   - Callback function for each iteration.
 * @param {Array|Object|NodeList} scope=null - Object/NodeList/Array that forEach is iterating over, to use as the this value when executing callback.
 * @returns {}
 */
let forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(let c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(let e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};

if (hamburgers.length > 0)
{
    forEach(hamburgers, function(hamburger) {
        hamburger.addEventListener("click", function()
        {
            this.classList.toggle("is-active");
        }, false);
    });
}

 /**
 * ajax is for later improvement
 */
// $("a").click(function(e) {
//     let href = this.href;
//     e.preventDefault();
//     $.ajax({
//         url: href,
//         success: function (result) {
//             document.querySelector('main').innerHTML = result;
//             // $("html").innerHTML = result;
//             window.history.pushState("", "", href);
//         }
//     });
// })
