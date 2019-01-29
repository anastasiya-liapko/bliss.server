'use strict';

$(function () {

  var form = $('#js-formEditRequest'),
    submitButton = $('#js-formEditRequestBtnSubmit');

  form.validate({
    highlight: function (element) {
      $(element).addClass('form-control_error');
    },
    unhighlight: function (element) {
      $(element).removeClass('form-control_error');
    },
    submitHandler: function (form) {
      var action = $(form).attr('action'),
        data = $(form).serialize();

      if (!submitButton.is(':disabled')) {
        Form.send(action, data, submitButton);
      }
    },
    rules: {
      tracking_id: {
        required: false,
        plain_text: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      }
    },
    messages: {
      tracking_id: {
        required: 'Это обязательное поле',
        plain_text: 'Поле не должно содержать HTML-теги'
      }
    },
    errorClass: 'form-control__error-text',
    errorElement: 'span'
  });

  form.on('keyup blur change', 'input, select, textarea', function () {
    Form.changeButtonStatus(form, submitButton);
  });

});
