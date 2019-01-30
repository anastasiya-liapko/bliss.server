'use strict';

$(function () {

  var form = $('#js-formCodeSms'),
    smsCodeInput = $('#js-formCodeSmsInputCode'),
    submitButton = $('#js-formCodeSmsBtnSubmit');

  smsCodeInput.inputmask('9999');

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
    ignore: [],
    rules: {
      sms_code: {
        required: true,
        number: true,
        normalizer: function (value) {
          return $.trim(value);
        }
      },
      terms: {
        required: true,
        plain_text: true
      }
    },
    messages: {
      sms_code: {
        required: 'Это обязательное поле',
        number: 'Код должен содержать только цифры'
      },
      terms: {
        required: 'Ознакомьтесь с условиями',
        plain_text: 'Поле не должно содержать HTML-теги'
      }
    },
    errorClass: 'error-text',
    errorElement: 'span'
  });

  form.on('keyup blur change', 'input, select, textarea', function () {
    Form.changeButtonStatus(form, submitButton);
  });

});
