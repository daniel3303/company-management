import $ from "jquery";
import 'bootstrap';
import '../../css/backend/_theme.scss';
import 'select2';
import "select2/dist/css/select2.css";
import "select2-bootstrap-theme/dist/select2-bootstrap.css";
import './form-collection';

// Toggle the side navigation
$("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
    var $sidebar = $('.sidebar');
    $("body").toggleClass("sidebar-toggled");
    $sidebar.toggleClass("toggled");
    if ($sidebar.hasClass("toggled")) {
        $('.sidebar .collapse').collapse('hide');
    }
});

// Close any open menu accordions when window is resized below 768px
$(window).resize(function() {
    if ($(window).width() < 768) {
        $('.sidebar .collapse').collapse('hide');
    }
});

// Prevent the content wrapper from scrolling when the fixed side navigation hovered over
$('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($(window).width() > 768) {
        var e0 = e.originalEvent,
            delta = e0.wheelDelta || -e0.detail;
        this.scrollTop += (delta < 0 ? 1 : -1) * 30;
        e.preventDefault();
    }
});

// Smooth scrolling using jQuery easing
$(document).on('click', 'a.scroll-to-top', function(e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
        scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
});


window.addEventListener("load", function (event) {
    apply_select2("select.form-control");
    //Mutation observer for new select boxes in the DOM
    var observer = new MutationObserver(function(mutations) {
        console.log(mutations);
        //loop through the detected mutations(added controls)
        mutations.forEach(function(mutation) {
            //addedNodes contains all detected new controls
            if (mutation && mutation.addedNodes) {
                mutation.addedNodes.forEach(function(elm) {
                    //only apply select2 to select elements
                    if (elm && $(elm).has("select.form-control")) {
                        apply_select2($(elm).find('select.form-control'));
                    }
                });
            }
        });
    });

    // pass in the target node, as well as the observer options
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

function apply_select2(selector) {
    $(selector).select2({
        language: "pt",
        theme: "bootstrap",
        allowClear: true,
        placeholder: "Selecione uma opção",
    });
}
