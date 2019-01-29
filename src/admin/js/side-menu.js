'use strict';

$(function () {
  var sideMenu = $('#js-sideMenu'),
    sideMenuToggleBtn = $('#js-sideMenuToggleButton');

  sideMenuToggleBtn.on('click', function () {
    sideMenu.toggleClass('side-menu_active');
  });

});
