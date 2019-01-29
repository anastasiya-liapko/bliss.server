'use strict';

$(function () {
  var modal = $('#js-modalSendRequest');

  if (modal.data('open')) {
    modal.modal({
      backdrop: 'static',
      keyboard: false
    });
  }
});
