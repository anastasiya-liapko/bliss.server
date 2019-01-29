'use strict';

$(function () {
  var modal = $('#js-modalWaitResponse');

  if (modal.data('open')) {
    modal.modal({
      backdrop: 'static',
      keyboard: false
    });
  }
});
