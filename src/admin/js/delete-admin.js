'use strict';

$(function () {
  var deleteBtn = $('.js-deleteAdmin');

  deleteBtn.on('click', function (event) {
    event.preventDefault();

    if (confirm('Вы уверены, что хотите удалить пользователя?')) {
      var currentBtn = $(this),
        action = currentBtn.data('action'),
        data = {
          id: currentBtn.data('id'),
          remove_id: currentBtn.data('remove-id')
        };

      if (!currentBtn.is(':disabled')) {
        Form.send(action, data, currentBtn);
      }
    }

  });

});
