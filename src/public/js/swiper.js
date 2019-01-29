'use strict';

$(function () {

  new Swiper('.swiper-container', {
    slidesPerView: 3,
    spaceBetween: 30,
    loop: true,
    breakpoints: {
      576: {
        slidesPerView: 1,
        spaceBetween: 10
      },
      768: {
        slidesPerView: 2
      }
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });

});
