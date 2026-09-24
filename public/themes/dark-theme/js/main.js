/* ==========================================================================
   HEZBUT TAWHEED THEME SCRIPTS
   Author: Antigravity
   ========================================================================== */

$(document).ready(function() {
    "use strict";

    // 1. Scroll To Top Button Show/Hide
    var scrollToTopBtn = $('#scrollToTopBtn');

    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            scrollToTopBtn.addClass('show');
        } else {
            scrollToTopBtn.removeClass('show');
        }
    });

    // 2. Click to Scroll to Top
    scrollToTopBtn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, 'slow');
    });

    // 3. Smooth transition for navbar sticky behavior
    var navbar = $('.navbar');
    $(window).scroll(function() {
        if ($(window).scrollTop() > 50) {
            navbar.addClass('shadow');
        } else {
            navbar.removeClass('shadow');
        }
    });

    // 4. Simple animations on hover for cards
    $('.ideology-card, .news-card, .team-card').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
});
