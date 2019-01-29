'use strict';

$(function () {

  $('.js-applicationChangeStatus').on('click', function () {
    var action = $(this).data('action');

    if (action) {
      Form.send(action);
    }
  });

});
