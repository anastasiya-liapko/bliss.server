'use strict';

$(function () {

  new Swiper('#js-sliderIntegrationPlugin', {
    slidesPerView: 4,
    spaceBetween: 20,
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
      nextEl: '#js-sliderIntegrationPluginNextBtn',
      prevEl: '#js-sliderIntegrationPluginPrevBtn',
    },
  });

});
