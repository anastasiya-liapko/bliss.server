'use strict';

$(function () {
  var form = $('#js-formPhoneNumber'),
    phoneInput = $('#js-formPhoneNumberInputPhone'),
    submitButton = $('#js-formPhoneNumberBtnSubmit');

  phoneInput.inputmask('+7(999)999-99-99');

  form.validate({
    highlight: function (element) {
      $(element).addClass('input_error');
    },
    unhighlight: function (element) {
      $(element).removeClass('input_error');
    },
    submitHandler: function (form) {
      var action = $(form).attr('action'),
        data = $(form).serialize();

      if (!submitButton.is(':disabled')) {
        Form.send(action, data, submitButton);
      }
    },
    rules: {
      phone: {
        required: true,
        phone_ru: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
    },
    messages: {
      phone: {
        required: 'Это обязательное поле',
        phone_ru: 'Введите номер в формате +7(xxx)xxx-xx-xx'
      }
    },
    errorClass: 'error-text',
    errorElement: 'span'
  });

  form.on('keyup blur change', 'input, select, textarea', function () {
    Form.changeButtonStatus(form, submitButton);
  });

});
