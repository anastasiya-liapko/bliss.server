'use strict';

$(function () {

  var form = $('#js-formSuccess'),
    smsCodeInput = $('#js-formSuccessInputCode'),
    submitButton = $('#js-formSuccessBtnSubmit');

  smsCodeInput.inputmask('9999',{'placeholder': ''}); // deleted placeholder

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
        minlength: 4, // added minlength
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
        number: 'Код должен содержать только цифры',
        minlength: 'Код должен содержать 4 символа' // added error text
      },
      terms: {
        required: 'Ознакомьтесь с условиями',
        plain_text: 'Поле не должно содержать HTML-теги'
      }
    },
    errorClass: 'error-text',
    errorElement: 'span'
  });

  form.on('keyup keydown blur change', 'input, select, textarea', function () { // added keydown
    Form.changeButtonStatus(form, submitButton);
  });

});
