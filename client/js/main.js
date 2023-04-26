(function ($) {
  "use strict";

  // Spinner
  var spinner = function () {
    setTimeout(function () {
      if ($("#spinner").length > 0) {
        $("#spinner").removeClass("show");
      }
    }, 1);
  };
  spinner();

  // Initiate the wowjs
  new WOW().init();

  // Fixed Navbar
  $(window).scroll(function () {
    if ($(window).width() < 992 && $(window).width() > 768) {
      if ($(this).scrollTop() > 45) {
        $(".fixed-top").addClass("bg-white shadow");
        $(".nav-item").removeClass("text-light");
        $(".active").addClass("text-primary");
      } else {
        $(".fixed-top").removeClass("bg-white shadow");
        $(".active").removeClass("text-primary");
        if (!window.location.href.includes("index")) {
          $(".nav-item").addClass("text-light");
          $(".active").removeClass("text-light");
        }
      }
    } else if ($(window).width() >= 992) {
      if ($(this).scrollTop() > 45) {
        $(".fixed-top").addClass("bg-white shadow").css("top", -45);
        $(".nav-item").removeClass("text-light");
        $(".active").addClass("text-primary");
      } else {
        $(".fixed-top").removeClass("bg-white shadow").css("top", 0);
        $(".active").removeClass("text-primary");
        if (!window.location.href.includes("index")) {
          $(".nav-item").addClass("text-light");
          $(".active").removeClass("text-light");
        }
      }
    }
    if ($(window).width() < 768) {
      if ($(this).scrollTop() > 45) {
        $(".fixed-top").addClass("bg-white shadow");
      } else {
        $(".fixed-top").removeClass("bg-white shadow");
      }
    }
  });

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $(".back-to-top").fadeIn(500);
    } else {
      $(".back-to-top").fadeOut(500);
    }
  });
  $(".back-to-top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 200, "easeInOutExpo");
    return false;
  });

  $(".testimonial-carousel").owlCarousel({
    autoplay: true,
    smartSpeed: 1000,
    margin: 25,
    loop: true,
    center: true,
    dots: false,
    nav: true,
    navText: [
      '<i class="bi bi-chevron-left"></i>',
      '<i class="bi bi-chevron-right"></i>',
    ],
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
      },
    },
  });
})(jQuery);

$(document).ready(function () {
  if ($(window).width() <= 768) {
    $(".navbar-nav").addClass("bg-primary");
  }
  $(window).resize();

  $(window).resize(function () {
    if ($(window).width() <= 768) {
      $(".navbar-nav").addClass("bg-primary");
    } else {
      $(".navbar-nav").removeClass("bg-primary");
    }
  });
});

