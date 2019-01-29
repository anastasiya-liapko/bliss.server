'use strict';

$(function () {
  var modal = $('#js-modalNoResponse');

  if (modal.data('open')) {
    modal.modal({
      backdrop: 'static',
      keyboard: false
    });
  }
});
